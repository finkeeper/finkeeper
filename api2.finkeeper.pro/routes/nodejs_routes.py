from fastapi import APIRouter
from services.nodejs_service import run_js
from pydantic import BaseModel  # ✅ Исправленный импорт
import json  # ✅ Добавляем импорт JSON



router = APIRouter()

class TransferRequest(BaseModel):
    recipient: str
    amount: str
    mnemonic: str

@router.get("/node/{command}")
async def call_node(command: str):
    """Вызывает Node.js-скрипт `index.js` с переданной командой"""
    result = await run_js(command)
    return {"result": result}

@router.get("/node/balance/{address}")
async def get_balance(address: str):
    """Получить баланс по адресу"""
    result = await run_js("balance", address)
    return {"balance": result}

@router.post("/node/transfer/")
async def transfer(request: TransferRequest):
    """Отправляет SUI и возвращает `digest` без ожидания подтверждения"""
    result = await run_js("transfer", request.recipient, request.amount, request.mnemonic)

    # ✅ Если result — строка, превращаем в JSON
    if isinstance(result, str):
        try:
            result = json.loads(result)
        except json.JSONDecodeError:
            return {"error": "Invalid JSON response from Node.js", "raw_output": result}

    return {"digest": result.get("digest", "unknown")}



@router.post("/node/create_wallet/")
async def create_wallet(hash: str):
    """Создаёт новый SUI-кошелек и возвращает его данные"""
    result = await run_js("create_wallet", hash)
    return {"wallet": result}
