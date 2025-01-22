from fastapi import APIRouter, HTTPException
from typing import List

from onchain.balance import Balance
from onchain.schemas import BalanceResponse, JettonBalanceResponse, BalanceUSDResponse

router = APIRouter()
balance_instance = Balance()

@router.get("/ton/{wallet_id}", response_model=BalanceResponse)
async def get_ton_balance(wallet_id: str):
    try:
        result = await balance_instance.get_ton_balance(wallet_id=wallet_id)
        return result
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/jetton/{wallet_id}/{jetton_id}", response_model=JettonBalanceResponse)
async def get_jetton_balance(wallet_id: str, jetton_id: str):
    try:
        result = await balance_instance.get_jetton_balance(wallet_id=wallet_id, jetton_id=jetton_id)
        return result
    except Exception as e:
        raise HTTPException(status_code=404, detail=str(e))

@router.get("/jettons/{wallet_id}", response_model=List[JettonBalanceResponse])
async def get_jettons_balance(wallet_id: str):
    try:
        result = await balance_instance.get_jettons_balance(wallet_id=wallet_id)
        return result
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/ton_usd/{wallet_id}", response_model=BalanceUSDResponse)
async def get_ton_balance_in_usd(wallet_id: str):
    try:
        usd_balance = await balance_instance.get_ton_balance_in_usd(wallet_id=wallet_id)
        return {"balance_usd": usd_balance}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/jetton_usd/{wallet_id}/{jetton_id}", response_model=BalanceUSDResponse)
async def get_jetton_balance_in_usd(wallet_id: str, jetton_id: str):
    try:
        usd_balance = await balance_instance.get_jetton_balance_in_usd(wallet_id=wallet_id, jetton_id=jetton_id)
        return {"balance_usd": usd_balance}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))