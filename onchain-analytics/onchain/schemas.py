from pydantic import BaseModel, Field
from typing import Optional, List, Union
from datetime import datetime

class User(BaseModel):
    chat_id: int
    wallets: List[str] = Field(default_factory=list)

class ActionSimplePreview(BaseModel):
    name: Optional[str]
    description: Optional[str]
    value: Optional[str]
    value_image: Optional[str]

class Address(BaseModel):
    root: str
    name: Optional[str]
    is_scam: Optional[bool]
    icon: Optional[str]
    is_wallet: Optional[bool]

class JettonDetails(BaseModel):
    name: str
    symbol: str
    address: str
    decimals: Optional[int] = 9
    image: Optional[str]
    description: Optional[str]
    websites: Optional[List[str]]
    social: Optional[List[str]]
    coingecko_id: Optional[str]

class TransactionSources(BaseModel):
    sender: Address
    recipient: Address

class TonTransaction(BaseModel):
    hash: str
    timestamp: datetime
    direction: str
    sources: TransactionSources
    value: float
    fees: float
    op_code: Optional[str]
    decoded_op_name: Optional[str]
    action_phase_success: Optional[bool]
    compute_gas_used: Optional[int]
    compute_exit_code: Optional[int]
    credit: Optional[float]
    block: Optional[str]
    state_update_old: Optional[str]
    state_update_new: Optional[str]
    status: str

class JettonTransaction(BaseModel):
    event_id: str
    timestamp: datetime
    direction: str
    jetton: JettonDetails
    sources: TransactionSources
    value: float
    comment: Optional[str]
    encrypted_comment: Optional[str]
    refund: Optional[dict]
    senders_wallet: Optional[str]
    recipients_wallet: Optional[str]
    simple_preview: Optional[ActionSimplePreview]
    status: str
    action_type: str

class UnifiedTransaction(BaseModel):
    transaction_id: str
    timestamp: datetime
    direction: str
    sources: TransactionSources
    value: float
    fees: Optional[float]
    jetton: Optional[JettonDetails]
    comment: Optional[str]
    status: str

def normalize_ton(value: int) -> float:
    return value / 1_000_000_000

def normalize_jetton(value: int, decimals: int) -> float:
    return value / (10 ** decimals)

class BalanceResponse(BaseModel):
    balance: float
    symbol: str
    name: str

class JettonBalanceResponse(BalanceResponse):
    balance: float
    symbol: str
    name: str
    root_address: str
    bounceable_address: str
    unbounceable_address: str

class JettonsBalanceResponse(BaseModel):
    balances: List[JettonBalanceResponse]

class BalanceUSDResponse(BaseModel):
    balance_usd: float
