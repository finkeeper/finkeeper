import redis.asyncio as redis
import json

class UserAlreadyExistsException(Exception):
    pass

class UserNotFoundException(Exception):
    pass

class WalletAlreadyAddedException(Exception):
    pass

class WalletNotFoundException(Exception):
    pass

class UserManager:
    _instance = None

    def __new__(cls, redis_url="redis://localhost:6379/0"):
        if not cls._instance:
            cls._instance = super().__new__(cls)
            cls._instance.redis_url = redis_url
            cls._instance.redis = None
        return cls._instance

    async def init_redis(self):
        if self.redis is None:
            self.redis = redis.from_url(self.redis_url, encoding="utf-8", decode_responses=True)

    async def close_redis(self):
        if self.redis:
            await self.redis.close()
            self.redis = None

    async def create_user(self, chat_id: int):
        await self.init_redis()
        key = f"user:{chat_id}"
        exists = await self.redis.exists(key)
        if exists:
            raise UserAlreadyExistsException("User already exists")
        user_data = {"chat_id": chat_id, "wallets": []}
        await self.redis.set(key, json.dumps(user_data))
        return user_data

    async def add_wallet(self, chat_id: int, wallet: str):
        await self.init_redis()
        key = f"user:{chat_id}"
        user_data_raw = await self.redis.get(key)
        if not user_data_raw:
            raise UserNotFoundException("User not found")
        user_data = json.loads(user_data_raw)
        if wallet in user_data["wallets"]:
            raise WalletAlreadyAddedException("Wallet already added")
        user_data["wallets"].append(wallet)
        await self.redis.set(key, json.dumps(user_data))
        return user_data

    async def remove_wallet(self, chat_id: int, wallet: str):
        await self.init_redis()
        key = f"user:{chat_id}"
        user_data_raw = await self.redis.get(key)
        if not user_data_raw:
            raise UserNotFoundException("User not found")
        user_data = json.loads(user_data_raw)
        if wallet not in user_data["wallets"]:
            raise WalletNotFoundException("Wallet not found")
        user_data["wallets"].remove(wallet)
        await self.redis.set(key, json.dumps(user_data))
        return user_data

    async def get_user(self, chat_id: int):
        await self.init_redis()
        key = f"user:{chat_id}"
        user_data_raw = await self.redis.get(key)
        if not user_data_raw:
            return None
        user_data = json.loads(user_data_raw)
        return user_data

    async def delete_user(self, chat_id: int):
        await self.init_redis()
        key = f"user:{chat_id}"
        await self.redis.delete(key)