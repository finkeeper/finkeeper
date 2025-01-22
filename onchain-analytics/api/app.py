from sys import prefix

from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

from api.handlers.coins import router as coins_router
from api.handlers.transactions import router as transactions_router
from api.handlers.balance import router as balance_router
from api.handlers.users import router as user_router
from api.handlers.dedust import router as dedust_router

app = FastAPI(
    title="FinKeeper OnChain API",
    version="1.0",
    description="API for retrieving wallet transaction information and wallet analysis.",
    docs_url="/docs",
    redoc_url="/redoc",
    openapi_url="/openapi.json",
)

app.include_router(coins_router, prefix="/coins", tags=["Coins"])
app.include_router(transactions_router, prefix="/transactions", tags=["Transactions"])
app.include_router(balance_router, prefix="/balance", tags=["Balance"])
app.include_router(user_router, prefix="/user", tags=["User"])
app.include_router(dedust_router, prefix="/dedust", tags=["Dedust"])

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)
