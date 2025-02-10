from fastapi import APIRouter, HTTPException
from services.navi_service import get_pool_info, get_test_json
from services.navi_service import deposit_to_navi, withdraw
import httpx  # 📌 Если нет, установи: pip install httpx



from pydantic import BaseModel  # ✅ Исправленный импорт

class DepositRequest(BaseModel):
    mnemonic: str
    token: str
    amount: int
    
class WithdrawRequest(BaseModel):
    mnemonic: str
    token: str
    amount: int


router = APIRouter()

@router.get("/navi/test_json")
async def fetch_test_json():
    """
    Возвращает тестовый JSON-ответ от Node.js API.
    """
    return await get_test_json()

@router.get("/navi/pool/{token_name}")
async def fetch_pool_info(token_name: str):
    """
    Получает информацию о пуле через Node.js API.

    Аргументы:
        token_name (str): Название токена (например, "SUI", "USDT", "USDC").

    Возвращает:
        JSON с ценой токена, ставками по займам/депозитам и другой информацией.
    """
    return await get_pool_info(token_name)


@router.post("/navi/deposit")
async def deposit(request: DepositRequest):
    """Депозит в Navi через FastAPI"""
    result = await deposit_to_navi(request.mnemonic, request.token, request.amount)
    return result


@router.post("/navi/withdraw")
async def withdraw_funds(request: WithdrawRequest):
    """Withdraw from Navi  FastAPI"""
    result = await withdraw(request.mnemonic, request.token, request.amount)
    return result
