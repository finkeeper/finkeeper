from pydantic import BaseModel
from datetime import datetime
from typing import List, Optional, Dict, Any
import httpx

DEDUST_GRAPHQL_URL = "https://api.dedust.io/v3/graphql"
GRAPHQL_HEADERS = {
    "accept": "*/*",
    "content-type": "application/json",
    "origin": "https://dedust.io",
    "referer": "https://dedust.io/"
}

query = """
query GetBoostsAndPools {
  boosts {
    asset
    budget
    endAt
    liquidityPool
    rewardPerDay
    startAt
  }
}
"""

def fetch_data():
    response = requests.post(GRAPHQL_URL, json={'query': query})
    if response.status_code == 200:
        return response.json()['data']
    else:
        raise Exception(f"Query failed with status code {response.status_code}: {response.text}")

class Token(BaseModel):
    address: str
    name: str
    symbol: str
    decimals: int


class Pool(BaseModel):
    address: str
    leftToken: Token
    rightToken: Token


class PoolWithAPY(BaseModel):
    liquidityPool: str
    asset: str
    apy: float
    startAt: str
    endAt: str
    rewardPerDay: float
    totalRewardBudget: float
    poolInfo: Optional[Pool] = None


async def fetch_pool_info(pool_address: str) -> Dict[str, Any]:
    """
    Fetch pool information from DeDust GraphQL API
    """
    query = """
    query GetPool($address: String!) {
        pool(address: $address) {
            address
            leftToken {
                address
                name
                symbol
                decimals
            }
            rightToken {
                address
                name
                symbol
                decimals
            }
        }
    }
    """

    variables = {
        "address": pool_address
    }

    payload = {
        "query": query,
        "variables": variables,
        "operationName": "GetPool"
    }

    async with httpx.AsyncClient() as client:
        try:
            response = await client.post(
                DEDUST_GRAPHQL_URL,
                headers=GRAPHQL_HEADERS,
                json=payload,
                timeout=10.0
            )
            response.raise_for_status()
            data = response.json()
            return data.get("data", {}).get("pool", {})
        except Exception as e:
            print(f"Error fetching pool info: {e}")
            return None


async def fetch_dedust_data() -> List[Dict[str, Any]]:
    """
    Fetch boost data from DeDust GraphQL API
    """
    query = """
    query GetBoosts {
        boosts {
            asset
            budget
            endAt
            liquidityPool
            rewardPerDay
            startAt
        }
    }
    """

    payload = {
        "query": query,
        "operationName": "GetBoosts"
    }

    async with httpx.AsyncClient() as client:
        response = await client.post(
            DEDUST_GRAPHQL_URL,
            headers=GRAPHQL_HEADERS,
            json=payload
        )
        data = response.json()
        return data.get("data", {}).get("boosts", [])


def calculate_apy(reward_per_day: float, total_budget: float) -> float:
    try:
        if total_budget == 0:
            return 0
        daily_rate = reward_per_day / total_budget
        apy = ((1 + daily_rate) ** 365 - 1) * 100
        return round(apy, 2)
    except Exception:
        return 0