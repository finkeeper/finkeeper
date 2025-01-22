from typing import List
from fastapi import APIRouter, HTTPException
from onchain.trasnactions import Transactions
from onchain.schemas import TonTransaction, JettonTransaction

router = APIRouter()
transactions_instance = Transactions()


@router.get("/ton/{wallet_id}", response_model=List[TonTransaction])
async def get_ton_transactions(wallet_id: str):
    try:
        ton_transactions = await transactions_instance.fetch_ton_transactions(wallet_id=wallet_id)
        return ton_transactions
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))


@router.get(
    "/jetton/{wallet_id}/{jetton_id}",
    response_model=List[JettonTransaction],
)
async def get_jetton_transactions(wallet_id: str, jetton_id: str):
    try:
        jetton_transactions = await transactions_instance.fetch_jetton_transactions(
            wallet_id=wallet_id, jetton_id=jetton_id
        )
        return jetton_transactions
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))