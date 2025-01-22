from fastapi import APIRouter, Query
from pydantic import BaseModel
import json

router = APIRouter()

@router.get("/all_pools", tags=["dedust"])
def get_all_pools():
    with open("pool_data.json", "r") as file:
        data = json.load(file)
    return data

@router.get("/pool_info", tags=["dedust"])
def get_pool_info(pool_name: str = Query(...)):
    with open("pool_data.json", "r") as file:
        data = json.load(file)
    for pool in data[0]["pools"]:
        if pool["name"] == pool_name:
            return {"timestamp": data[0]["timestamp"], "pool_info": pool}
    return {"error": "Pool not found"}

class HealthCheck(BaseModel):
    status: str = "OK"

