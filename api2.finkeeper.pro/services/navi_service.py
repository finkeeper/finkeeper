import httpx  # 📌 Установи библиотеку, если её нет: pip install httpx

NODE_API_URL = "http://localhost:3001"  # 📌 Если Node.js API работает на другом порту/сервере, измени этот URL

async def get_test_json():
    """Вызывает `/test_json` API на Node.js"""
    async with httpx.AsyncClient() as client:
        response = await client.get(f"{NODE_API_URL}/test_json")
        return response.json()

async def get_pool_info(token_name: str):
    """Вызывает `/pool/{token_name}` API на Node.js"""
    async with httpx.AsyncClient() as client:
        response = await client.get(f"{NODE_API_URL}/pool/{token_name}")
        return response.json()

async def deposit_to_navi(mnemonic: str, token: str, amount: int):
    """Вызывает `/deposit` API на Node.js для выполнения депозита"""
    async with httpx.AsyncClient() as client:
        response = await client.post(f"{NODE_API_URL}/deposit", json={
            "mnemonic": mnemonic,
            "token": token,
            "amount": amount
        })
        return response.json()


async def withdraw(mnemonic: str, token: str, amount: int):
    """Вызывает /withdraw API в Navi.js"""
    async with httpx.AsyncClient() as client:
        response = await client.post(
            f"{NODE_API_URL}/withdraw",
            json={"mnemonic": mnemonic, "token": token, "amount": amount}
        )
        return response.json()
