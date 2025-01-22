from datetime import datetime
import requests

from onchain.trasnactions import Transactions
from onchain.coingecko import CoinGeckoProvider

class Balance(Transactions):
    _instance = None

    def __new__(cls, *args, **kwargs):
        if not cls._instance:
            cls._instance = super().__new__(cls, *args, **kwargs)
        return cls._instance

    def __init__(self):
        super().__init__()
        self.cg = CoinGeckoProvider()

    async def get_ton_balance(self, wallet_id: str):
        account = await self.tonapi.accounts.get_info(account_id=wallet_id)
        return {
            "balance": account.balance.root / 1_000_000_000,
            "symbol": "TON",
            "name": "TONCOIN"
        }

    async def get_jetton_balance(self, wallet_id: str, jetton_id: str):
        jettons_balance = await self.tonapi.accounts.get_jettons_balances(account_id=wallet_id)
        for jetton in jettons_balance.balances:
            if jetton.jetton.address.to_userfriendly(is_bounceable=True) == jetton_id \
                    or jetton.jetton.address.to_userfriendly(is_bounceable=False) == jetton_id \
                    or jetton.jetton.address.root == jetton_id:
                return {
                        "balance": int(jetton.balance) / pow(10, jetton.jetton.decimals),
                        "symbol": jetton.jetton.symbol if 'USD₮' not in jetton.jetton.symbol else jetton.jetton.symbol.replace('USD₮', 'USDT'),
                        "name": jetton.jetton.name,
                        "root_address": jetton.jetton.address.root,
                        "bounceable_address": jetton.jetton.address.to_userfriendly(is_bounceable=True),
                        "unbounceable_address": jetton.jetton.address.to_userfriendly(is_bounceable=False)
                    }

        return Exception("NotFound")

    async def get_jettons_balance(self, wallet_id: str):
        jettons_balance = await self.tonapi.accounts.get_jettons_balances(account_id=wallet_id)
        res = list()
        for jetton in jettons_balance.balances:
            res.append({
                        "balance": int(jetton.balance) / pow(10, jetton.jetton.decimals),
                        "symbol": jetton.jetton.symbol if 'USD₮' not in jetton.jetton.symbol else jetton.jetton.symbol.replace('USD₮', 'USDT'),
                        "name": jetton.jetton.name,
                        "root_address": jetton.jetton.address.root,
                        "bounceable_address": jetton.jetton.address.to_userfriendly(is_bounceable=True),
                        "unbounceable_address": jetton.jetton.address.to_userfriendly(is_bounceable=False)
            })

        return res

    async def get_ton_balance_in_usd(self, wallet_id: str):
        ton_balance = await self.get_ton_balance(wallet_id=wallet_id)
        return float(self.cg.get_coin_usd_price(coingecko_id="the-open-network")) * float(ton_balance.get("balance"))

    async def get_jetton_balance_in_usd(self, wallet_id: str, jetton_id: str):
        try:
            jetton_balance = await self.get_jetton_balance(wallet_id=wallet_id, jetton_id=jetton_id)
            coingecko_id = self.cg.get_id_by_address(address=jetton_id)
            if coingecko_id is None:
                return 0
            else:
                jetton_usd_price = self.cg.get_coin_usd_price(coingecko_id=coingecko_id)
                return float(jetton_balance.get("balance")) * float(jetton_usd_price)
        except requests.exceptions.HTTPError or ValueError or KeyError:
            return 0

    async def get_ton_balance_on_date(self, wallet_id: str, tx_datetime: datetime) -> float:
        ton_txs = await self.fetch_ton_transactions(wallet_id=wallet_id)
        sorted_ton_txs = sorted(ton_txs, key=lambda x: x.timestamp, reverse=True)
        filtered_transactions = [tx for tx in sorted_ton_txs if tx.timestamp >= tx_datetime]
        balance = await self.get_ton_balance(wallet_id=wallet_id)
        balance = balance['balance']
        for tx in filtered_transactions:
            if tx.direction == 'out' and tx.status == 'ok':
                balance += tx.value + tx.fees
            elif tx.direction == 'in' and tx.status == 'ok':
                balance -= tx.value

        return balance

    async def get_jetton_balance_on_date(self, wallet_id: str, jetton_id: str, tx_datetime: datetime) -> float:
        jetton_txs = await self.fetch_jetton_transactions(wallet_id=wallet_id, jetton_id=jetton_id)
        sorted_jetton_txs = sorted(jetton_txs, key=lambda x: x.timestamp, reverse=True)
        filtered_transactions = [tx for tx in sorted_jetton_txs if tx.timestamp >= tx_datetime]
        for tx in filtered_transactions:
            print(tx)
            print("-"*40)
        balance = await self.get_jetton_balance(wallet_id=wallet_id, jetton_id=jetton_id)
        balance = balance['balance']
        for tx in filtered_transactions:
            if tx.direction == 'out' and tx.status == 'ok':
                balance -= tx.value
            elif tx.direction == 'in' and tx.status == 'ok':
                balance += tx.value

        return balance

if __name__ == "__main__":
    import asyncio

    async def main():
        b = Balance()
        #print(await b.get_ton_balance(wallet_id="UQCsHxqj-FEuX4Sgy_dmE50xsq_ch0yFd81dSIeAhQIbfRx6"))
        #print(await b.get_jetton_balance(wallet_id="UQCsHxqj-FEuX4Sgy_dmE50xsq_ch0yFd81dSIeAhQIbfRx6", jetton_id="EQCvxJy4eG8hyHBFsZ7eePxrRsUQSFE_jpptRAYBmcG_DOGS"))
        #print(await b.get_jettons_balance(wallet_id="UQCsHxqj-FEuX4Sgy_dmE50xsq_ch0yFd81dSIeAhQIbfRx6"))
        #print(await b.get_ton_balance_in_usd(wallet_id="UQCsHxqj-FEuX4Sgy_dmE50xsq_ch0yFd81dSIeAhQIbfRx6"))
        #print(await b.get_jetton_balance_in_usd(wallet_id="UQCsHxqj-FEuX4Sgy_dmE50xsq_ch0yFd81dSIeAhQIbfRx6", jetton_id="EQAvlWFDxGF2lXm67y4yzC17wYKD9A0guwPkMs1gOsM__NOT"))
        #print(await b.get_ton_balance_on_date(wallet_id="UQCsHxqj-FEuX4Sgy_dmE50xsq_ch0yFd81dSIeAhQIbfRx6", tx_datetime=datetime(2024,9,1)))
        #print(await b.get_jetton_balance_on_date(wallet_id="UQCsHxqj-FEuX4Sgy_dmE50xsq_ch0yFd81dSIeAhQIbfRx6", jetton_id="EQAvlWFDxGF2lXm67y4yzC17wYKD9A0guwPkMs1gOsM__NOT", tx_datetime=datetime(2024, 5, 18)))
    asyncio.run(main())
