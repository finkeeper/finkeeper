import os
import datetime
from dotenv import load_dotenv
from pytonapi import AsyncTonapi
from onchain.jetton_mixin import JettonReaderMixin
from onchain.schemas import *

class Transactions(JettonReaderMixin):
    _instance = None

    def __new__(cls, *args, **kwargs):
        if not cls._instance:
            cls._instance = super().__new__(cls, *args, **kwargs)
        return cls._instance

    def __init__(self):
        load_dotenv()
        self.tonapi = AsyncTonapi(api_key=os.getenv("TON_API_KEY"))
        self.jettons = self.parse_tokens_from_file(
            file_path=f'{os.path.dirname(os.path.abspath(__file__))}/jettons.yaml'
        )

    def get_jetton_by_address(self, address: str) -> Optional[dict]:
        for jetton in self.jettons:
            for symbol, details in jetton.items():
                jetton_address = details.get('address')
                if jetton_address == address:
                    details['symbol'] = symbol
                    return details
        return None

    async def fetch_ton_transactions(self, wallet_id: str) -> List[TonTransaction]:
        transactions = []
        last_lt = None

        while True:
            tx = await self.tonapi.blockchain.get_account_transactions(
                account_id=wallet_id,
                limit=1000,
                before_lt=last_lt
            )
            if not tx.transactions:
                break

            last_lt = tx.transactions[-1].lt

            for t in tx.transactions:
                direction = "out" if t.out_msgs and t.out_msgs[0].value > 0 else "in"
                sender = t.out_msgs[0].source if direction == "out" else t.in_msg.source
                recipient = t.out_msgs[0].destination if direction == "out" else t.in_msg.destination

                transactions.append(
                    TonTransaction(
                        hash=t.hash,
                        timestamp=datetime.fromtimestamp(t.utime),
                        direction=direction,
                        sources=TransactionSources(
                            sender=Address(
                                root=sender.address.root,
                                name=sender.name,
                                is_scam=sender.is_scam,
                                icon=sender.icon,
                                is_wallet=sender.is_wallet,
                            ),
                            recipient=Address(
                                root=recipient.address.root,
                                name=recipient.name,
                                is_scam=recipient.is_scam,
                                icon=recipient.icon,
                                is_wallet=recipient.is_wallet,
                            ),
                        ),
                        value=normalize_ton(t.out_msgs[0].value if direction == "out" else t.in_msg.value),
                        fees=normalize_ton(t.total_fees),
                        op_code=t.in_msg.op_code,
                        decoded_op_name=t.in_msg.decoded_op_name,
                        action_phase_success=t.action_phase.success if t.action_phase else None,
                        compute_gas_used=t.compute_phase.gas_used if t.compute_phase else None,
                        compute_exit_code=t.compute_phase.exit_code if t.compute_phase else None,
                        credit=t.credit_phase.credit if t.credit_phase else None,
                        block=t.block,
                        state_update_old=t.state_update_old,
                        state_update_new=t.state_update_new,
                        status="ok" if t.success else "failed",
                    )
                )
        return transactions

    async def fetch_jetton_transactions(self, wallet_id: str, jetton_id: str) -> List[JettonTransaction]:
        transactions = []
        last_lt = None

        while True:
            response = await self.tonapi.accounts.get_jettons_history_by_jetton(
                account_id=wallet_id,
                limit=1000,
                before_lt=last_lt,
                jetton_id=jetton_id,
            )

            transactions.extend(response.events)

            if len(response.events) < 1000:
                break

            last_lt = response.events[-1].lt

        result = []
        for t in transactions:
            if t.actions and t.actions[0].type == "JettonTransfer":
                jetton_address = t.actions[0].JettonTransfer.jetton.address.root
                jetton_data = self.get_jetton_by_address(jetton_address)

                if jetton_data:
                    jetton_details = JettonDetails(
                        name=jetton_data.get('name'),
                        symbol=jetton_data.get('symbol'),
                        address=jetton_data.get('address'),
                        decimals=int(jetton_data.get('decimals', 9)),
                        image=jetton_data.get('image'),
                        description=jetton_data.get('description'),
                        websites=jetton_data.get('websites'),
                        social=jetton_data.get('social'),
                        coingecko_id=jetton_data.get('coingecko_id')
                    )
                else:
                    jetton_details = JettonDetails(
                        name=t.actions[0].JettonTransfer.jetton.name,
                        symbol=t.actions[0].JettonTransfer.jetton.symbol,
                        address=jetton_address,
                        decimals=t.actions[0].JettonTransfer.jetton.decimals,
                        image=t.actions[0].JettonTransfer.jetton.image,
                        description=None,
                        websites=None,
                        social=None,
                        coingecko_id=None
                    )

                result.append(
                    JettonTransaction(
                        event_id=t.event_id,
                        timestamp=datetime.fromtimestamp(t.timestamp),
                        direction="out" if t.actions[0].JettonTransfer.sender.address.root == wallet_id else "in",
                        jetton=jetton_details,
                        sources=TransactionSources(
                            sender=Address(
                                root=t.actions[0].JettonTransfer.sender.address.to_userfriendly(is_bounceable=False),
                                name=t.actions[0].JettonTransfer.sender.name,
                                is_scam=t.actions[0].JettonTransfer.sender.is_scam,
                                icon=t.actions[0].JettonTransfer.sender.icon,
                                is_wallet=t.actions[0].JettonTransfer.sender.is_wallet,
                            ),
                            recipient=Address(
                                root=t.actions[0].JettonTransfer.recipient.address.to_userfriendly(is_bounceable=False),
                                name=t.actions[0].JettonTransfer.recipient.name,
                                is_scam=t.actions[0].JettonTransfer.recipient.is_scam,
                                icon=t.actions[0].JettonTransfer.recipient.icon,
                                is_wallet=t.actions[0].JettonTransfer.recipient.is_wallet,
                            ),
                        ),
                        value=normalize_jetton(
                            int(t.actions[0].JettonTransfer.amount),
                            jetton_details.decimals,
                        ),
                        comment=t.actions[0].JettonTransfer.comment or "",
                        encrypted_comment=t.actions[0].JettonTransfer.encrypted_comment,
                        refund=t.actions[0].JettonTransfer.refund,
                        senders_wallet=t.actions[0].JettonTransfer.senders_wallet,
                        recipients_wallet=t.actions[0].JettonTransfer.recipients_wallet,
                        simple_preview=ActionSimplePreview(
                            name=t.actions[0].simple_preview.name,
                            description=t.actions[0].simple_preview.description,
                            value=t.actions[0].simple_preview.value,
                            value_image=t.actions[0].simple_preview.value_image,
                        ),
                        status="ok" if t.actions[0].status == "ok" else "failed",
                        action_type=t.actions[0].type,
                    )
                )
        return result


if __name__ == "__main__":
    import asyncio

    async def main():
        t = Transactions()
        print(t.jettons)
        # ton_txs = await t.fetch_ton_transactions(wallet_id="UQCsHxqj-FEuX4Sgy_dmE50xsq_ch0yFd81dSIeAhQIbfRx6")
        # for tx in ton_txs:
        #     print(tx)
        #     print("-"*40)

        # jettons_txs = await t.fetch_jetton_transactions(wallet_id="UQCsHxqj-FEuX4Sgy_dmE50xsq_ch0yFd81dSIeAhQIbfRx6", jetton_id="EQCvxJy4eG8hyHBFsZ7eePxrRsUQSFE_jpptRAYBmcG_DOGS")
        # for tx in jettons_txs:
        #     print(tx)
        #     print("-"*40)

    asyncio.run(main())