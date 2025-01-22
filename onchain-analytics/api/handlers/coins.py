from fastapi import APIRouter
from onchain.trasnactions import Transactions

router = APIRouter()
transactions_instance = Transactions()


@router.get("/")
async def get_coins():
    coins = []
    for jetton in transactions_instance.jettons:
        for symbol, details in jetton.items():
            details["symbol"] = symbol
            if 'decimals' not in details or details['decimals'] is None:
                details['decimals'] = 9
            coins.append(details)
    return coins
