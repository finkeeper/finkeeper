from onchain.balance import Balance

class Portfolio(Balance):
    _instance = None

    def __new__(cls, *args, **kwargs):
        if not cls._instance:
            cls._instance = super().__new__(cls, *args, **kwargs)
        return cls._instance

    def __init__(self):
        super().__init__()

    async def get_asset_distribution(self, wallet_id: str):

        ton_balance = await self.get_ton_balance(wallet_id=wallet_id)
        jettons_balance = await self.get_jettons_balance(wallet_id=wallet_id)
        jettons_balance.append(ton_balance)

        current_prices = dict()
        total_amount = 0

        for asset in jettons_balance:
            if asset['symbol'] == 'TON':
                current_prices['TON'] = await self.get_ton_balance_in_usd(wallet_id=wallet_id)
                total_amount += float(current_prices.get('TON'))
            else:
                print(asset['root_address'])
                jetton_usd_balance = await self.get_jetton_balance_in_usd(
                    wallet_id=wallet_id,
                    jetton_id=asset['root_address']
                )
                print(jetton_usd_balance)
                total_amount += float(jetton_usd_balance)
                current_prices[asset['symbol']] = jetton_usd_balance

        print("TOTAL AMOUNT: ", total_amount)
        print(current_prices)



if __name__ == "__main__":
    import asyncio
    async def main():
        p = Portfolio()
        await p.get_asset_distribution(wallet_id="UQCsHxqj-FEuX4Sgy_dmE50xsq_ch0yFd81dSIeAhQIbfRx6")

    asyncio.run(main())