import datetime

from pycoingecko import CoinGeckoAPI
from dotenv import load_dotenv
import os
from onchain.jetton_mixin import JettonReaderMixin
from pytonapi import Tonapi

class CoinGeckoProvider(JettonReaderMixin):
    _instance = None

    def __new__(cls, *args, **kwargs):
        if not cls._instance:
            cls._instance = super().__new__(cls, *args, **kwargs)
        return cls._instance

    def __init__(self):
        load_dotenv()
        self.cg = CoinGeckoAPI(demo_api_key=os.getenv("COIN_GECKO_API_KEY"))
        self.jettons = self.parse_tokens_from_file(
            file_path=f'{os.path.dirname(os.path.abspath(__file__))}/jettons.yaml'
        )
        self.tonapi = Tonapi(api_key=os.getenv("TON_API_KEY"))

    def get_coin_list(self):
        return self.cg.get_coins_list()

    def get_id_by_address(self, address: str):
        print(address)
        account = self.tonapi.accounts.get_info(account_id=address)
        for adr in self.jettons:
            data = list(adr.values())
            if (data[0]['address'] == account.address.to_raw()) \
                    or (data[0]['address'] == account.address.to_userfriendly(is_bounceable=True)) \
                    or (data[0]['address'] == account.address.to_userfriendly(is_bounceable=False)):
                return data[0]['coingecko_id'] if data[0]['coingecko_id'] != 'null' else None

    def get_coin_usd_price(self, coingecko_id: str):
        try:
            return self.cg.get_coin_by_id(id=coingecko_id)['market_data']['current_price']['usd']
        except Exception:
            return 0

    def get_coin_usd_price_on_date(self, coingecko_id: str, input_date: datetime.date):
        return self.cg.get_coin_history_by_id(id=coingecko_id, date=self.date_to_string(input_date=input_date))['market_data']['current_price']['usd']

    @staticmethod
    def date_to_string(input_date: datetime.date) -> str:
        return f"{input_date.day}-{input_date.month}-{input_date.year}"



if __name__ == "__main__":
    c = CoinGeckoProvider()
    #print(c.get_id_by_address(address="0:afc49cb8786f21c87045b19ede78fc6b46c51048513f8e9a6d44060199c1bf0c"))
    # acc = c.tonapi.accounts.get_info(account_id="EQCvxJy4eG8hyHBFsZ7eePxrRsUQSFE_jpptRAYBmcG_DOGS")
    # print(acc.address.to_raw())
    #print(c.get_coin_usd_price(coingecko_id=dogs_id))
    #print(c.get_coin_usd_price_on_date(coingecko_id=dogs_id, input_date=datetime.date(2024,10,1)))