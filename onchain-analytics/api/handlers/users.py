from fastapi import APIRouter, HTTPException

from api.user_manager import (
    UserManager,
    UserAlreadyExistsException,
    UserNotFoundException,
    WalletAlreadyAddedException,
    WalletNotFoundException,
)
from onchain.schemas import User

router = APIRouter()

user_manager = UserManager(redis_url="redis://localhost:6379/0")

@router.post("/", response_model=User)
async def create_user(chat_id: int):
    try:
        user_data = await user_manager.create_user(chat_id=chat_id)
        return user_data
    except UserAlreadyExistsException as e:
        raise HTTPException(status_code=400, detail=str(e))
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.post("/{chat_id}/wallets", response_model=User)
async def add_wallet(chat_id: int, wallet: str):
    try:
        user_data = await user_manager.add_wallet(chat_id=chat_id, wallet=wallet)
        return user_data
    except UserNotFoundException as e:
        raise HTTPException(status_code=404, detail=str(e))
    except WalletAlreadyAddedException as e:
        raise HTTPException(status_code=400, detail=str(e))
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.delete("/{chat_id}/wallets", response_model=User)
async def remove_wallet(chat_id: int, wallet: str):
    try:
        user_data = await user_manager.remove_wallet(chat_id=chat_id, wallet=wallet)
        return user_data
    except UserNotFoundException as e:
        raise HTTPException(status_code=404, detail=str(e))
    except WalletNotFoundException as e:
        raise HTTPException(status_code=400, detail=str(e))
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/{chat_id}", response_model=User)
async def get_user(chat_id: int):
    try:
        user_data = await user_manager.get_user(chat_id=chat_id)
        if user_data:
            return user_data
        else:
            raise UserNotFoundException("User not found")
    except UserNotFoundException as e:
        raise HTTPException(status_code=404, detail=str(e))
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.delete("/{chat_id}", response_model=dict)
async def delete_user(chat_id: int):
    try:
        await user_manager.delete_user(chat_id=chat_id)
        return {"detail": "User deleted"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))