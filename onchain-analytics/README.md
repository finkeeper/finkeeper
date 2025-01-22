# FinKeeper | Onchain analytics service
This service is designed for onchain analytics of user's wallets. It provides portfolio analytics methods, transaction histories, balances in different currencies, asset allocation, etc.

In the future it is planned to connect exchanges and other blockchains and wallets to build more extensive analytics for the user. 

## Tech Docs
### Dedust Pools APR parser
```
Actual endpoint:
http://94.141.102.189:8000/docs
http://94.141.102.189:8000/pool_info?pool_name=AquaUSD/USDT
http://94.141.102.189:8000/all_pools

Endpoint in new API version: host:8000/dedust/<method>
```

##### /all_pools
```json
[
  {
    "timestamp": "2024-11-22 03:02:06",
    "pools": [
      {
        "name": "TON/USDT",
        "tvl": "$43.23M",
        "volume": "$8.97M",
        "fees": "$8.97K",
        "apr": "19.29%"
      },
      {
        "name": "tsTON/USDT",
        "tvl": "$19.32M",
        "volume": "$1.15M",
        "fees": "$2.87K",
        "apr": "17.77%"
      },
      {
        "name": "stTON/USDT",
        "tvl": "$6.32M",
        "volume": "$298.7K",
        "fees": "$746.76",
        "apr": "15.59%"
      }
      ...
]}]
```

##### /pool_info?pool_name=AquaUSD/USDT
```json
{
  "timestamp": "2024-11-22 03:02:06",
  "pool_info": {
    "name": "AquaUSD/USDT",
    "tvl": "$919.82K",
    "volume": "$1.2K",
    "fees": "$0.6",
    "apr": "42.76%"
  }
}
```

### OnChain Analytics

#### Swagger available ```/docs```

#### 1. Coins info list
```bash
// GET /coins/

curl -X 'GET' \
  'http://127.0.0.1:8000/coins/' \
  -H 'accept: application/json'
```

```json
[
  {
    "address": "EQAu7qxfVgMg0tpnosBpARYOG--W1EUuX_5H_vOQtTVuHnrn",
    "coingecko_id": "gridcoin-research",
    "description": "The Open Network Charity Token",
    "image": "https://cyan-indirect-ant-156.mypinata.cloud/ipfs/QmTVVeutSCe4emnH7nhwKB98TuzASCwf8Jyvmx8MXnhuwX",
    "name": "Grouche coin",
    "social": [
      "https://t.me/grouche_coin",
      "https://t.me/grouche_chat"
    ],
    "symbol": "GRC",
    "websites": [
      "https://grouche.com"
    ],
    "decimals": 9
  },
  {
    "address": "EQDX9OC6Bq3oTeNS6Ykj6WygNlvCHEiiQeXTIDGtxQlaQ_FX",
    "coingecko_id": "grimoire-finance-token",
    "description": "$GRIM is a meme coin on the TON Blockchain.",
    "image": "https://i.postimg.cc/0Qrn9PMk/IMG-0983.jpg",
    "name": "Grim Reaper",
    "social": [
      "https://t.me/grimreaperton",
      "https://x.com/grimreapercoin"
    ],
    "symbol": "GRIM",
    "decimals": 9
  },
  {
    "address": "EQB5PvZCn-GeB8zsyz96XuXAXZ_vCKGnebClc2ZnGhYxj7pR",
    "coingecko_id": null,
    "decimals": 9,
    "description": "Cat Teftel, the mischievously adorable feline sticker pack, emerges from the depths of cyberspace, embodying the whimsical spirit of Telegram's vibrant sticker culture with each playful meow and animated tail flick.",
    "image": "https://imgur.com/a/TwZFKV6",
    "name": "Cat Teftel",
    "social": [
      "https://x.com/CatTeftelOnTon",
      "https://t.me/catteftelportal"
    ],
    "symbol": "TEFTEL",
    "websites": [
      "https://catteftelonton.xyz/"
    ]
  },
  {
    "address": "0:77f0c23f335359a05ae3c4efeb4c51454af0cb0f988a17ed63d4355e495c8fc2",
    "coingecko_id": "libra-3",
    "description": "In crypto with balance",
    "image": "https://github.com/Rebalancer/picture/blob/main/libra%20normalmente.png?raw=true",
    "name": "Libration",
    "social": [
      "https://t.me/rebalancer_ton",
      "https://x.com/REBALANCER_TON"
    ],
    "symbol": "LIBRA",
    "websites": [
      "https://rebalancer.pro/libration"
    ],
    "decimals": 9
  }
  ...
]
```
#### 2. TON transactions
```bash
// GET /transactions/ton/{wallet_id}
curl -X 'GET' \
  'http://127.0.0.1:8000/transactions/ton/<wallet_id>' \
  -H 'accept: application/json'
```
```json
[
  {
    "hash": "d12e44882585d617c6e7b5aa28279d5c3619822251ce54873f69c5aabf923f53",
    "timestamp": "2024-11-21T17:20:57",
    "direction": "in",
    "sources": {
      "sender": {
        "root": "0:2a91854e0ad42362691902dbe11978ab4c994b9f9182db8a32ad7c28cacaea39",
        "name": null,
        "is_scam": false,
        "icon": null,
        "is_wallet": false
      },
      "recipient": {
        "root": "0:ac1f1aa3f8512e5f84a0cbf766139d31b2afdc874c8577cd5d48878085021b7d",
        "name": null,
        "is_scam": false,
        "icon": null,
        "is_wallet": true
      }
    },
    "value": 0.063559999,
    "fees": 0.000396408,
    "op_code": "0xd53276db",
    "decoded_op_name": "excess",
    "action_phase_success": true,
    "compute_gas_used": 991,
    "compute_exit_code": 0,
    "credit": 63559999,
    "block": "(0,a000000000000000,47096151)",
    "state_update_old": "198b9424977626c33fbd90e00b048e5a6143be538d9f6f4f862e966c323d8588",
    "state_update_new": "028e49125716b7d4193c62dc1c9a67d946c1afa1926a333c44b13dc7eb48bbc4",
    "status": "ok"
  },
  ...
]
```

#### 3. Jetton transactions
```bash
// GET /transactions/jetton/{wallet_id}/{jetton_id}
curl -X 'GET' \
  'http://127.0.0.1:8000/transactions/jetton/UQCsHxqj-<wallet_id>/<jetton_address>' \
  -H 'accept: application/json'
```

```json
[
  {
    "event_id": "f8ad36d89dba0866d169f7ec92e3b41e6fb02070993ffe3cc9d4cca47b904a24",
    "timestamp": "2024-09-25T14:02:11",
    "direction": "in",
    "jetton": {
      "name": "Dogs",
      "symbol": "DOGS",
      "address": "0:afc49cb8786f21c87045b19ede78fc6b46c51048513f8e9a6d44060199c1bf0c",
      "decimals": 9,
      "image": "https://cache.tonapi.io/imgproxy/6Pb0sBFy_AzW6l39EIHGs-Iz4eLbbZUh8AYY_Xq-rcg/rs:fill:200:200:1/g:no/aHR0cHM6Ly9jZG4uZG9ncy5kZXYvZG9ncy5wbmc.webp",
      "description": null,
      "websites": null,
      "social": null,
      "coingecko_id": null
    },
    "sources": {
      "sender": {
        "root": "UQCtkG75GbHcDwN_ArpAkzD1Q-k0ou6goCzpBWnZP4ZjLafF",
        "name": null,
        "is_scam": false,
        "icon": null,
        "is_wallet": true
      },
      "recipient": {
        "root": "UQCsHxqj-FEuX4Sgy_dmE50xsq_ch0yFd81dSIeAhQIbfRx6",
        "name": null,
        "is_scam": false,
        "icon": null,
        "is_wallet": true
      }
    },
    "value": 7950,
    "comment": "",
    "encrypted_comment": null,
    "refund": null,
    "senders_wallet": "0:da81ab19fd68bea49393fcc579a9078e8d2bb6372d45e98dbd7c251e2a4e02b3",
    "recipients_wallet": "0:e04b0086d9e796078ffe8254968355a515703f944137bc12b0949f8b751fa3a2",
    "simple_preview": {
      "name": "Jetton Transfer",
      "description": "Transferring 7950 Dogs",
      "value": "7950 Dogs",
      "value_image": "https://cache.tonapi.io/imgproxy/6Pb0sBFy_AzW6l39EIHGs-Iz4eLbbZUh8AYY_Xq-rcg/rs:fill:200:200:1/g:no/aHR0cHM6Ly9jZG4uZG9ncy5kZXYvZG9ncy5wbmc.webp"
    },
    "status": "ok",
    "action_type": "JettonTransfer"
  },
  ...
]
```

#### 4. TON Balance
```bash
// GET /balance/ton/{wallet_id}
curl -X 'GET' \
  'http://127.0.0.1:8000/balance/ton/<wallet_id>' \
  -H 'accept: application/json'
```
```json
{
  "balance": 21.685086153,
  "symbol": "TON",
  "name": "TONCOIN"
}
```

#### 5. Jetton Balance
```bash
// GET /balance/jetton/{wallet_id}/{jetton_id}
curl -X 'GET' \
  'http://127.0.0.1:8000/balance/jetton/<wallet_id>/{jetton_id}' \
  -H 'accept: application/json'
```
```json
{
    "balance": 639.828411739,
    "symbol": "NOT",
    "name": "Notcoin",
    "root_address": "0:2f956143c461769579baef2e32cc2d7bc18283f40d20bb03e432cd603ac33ffc",
    "bounceable_address": "EQAvlWFDxGF2lXm67y4yzC17wYKD9A0guwPkMs1gOsM__NOT",
    "unbounceable_address": "UQAvlWFDxGF2lXm67y4yzC17wYKD9A0guwPkMs1gOsM__I5W"
  }
```

#### 6. Jettons Balance
```bash
// GET /balance/jettons/{wallet_id}
curl -X 'GET' \
  'http://127.0.0.1:8000/balance/jetton/<wallet_id>' \
  -H 'accept: application/json'
```
```json
[
  {
    "balance": 639.828411739,
    "symbol": "NOT",
    "name": "Notcoin",
    "root_address": "0:2f956143c461769579baef2e32cc2d7bc18283f40d20bb03e432cd603ac33ffc",
    "bounceable_address": "EQAvlWFDxGF2lXm67y4yzC17wYKD9A0guwPkMs1gOsM__NOT",
    "unbounceable_address": "UQAvlWFDxGF2lXm67y4yzC17wYKD9A0guwPkMs1gOsM__I5W"
  },
  {
    "balance": 0.477335765,
    "symbol": "GEMSTON",
    "name": "GEMSTON",
    "root_address": "0:57e8af5a5d59779d720d0b23cf2fce82e0e355990f2f2b7eb4bba772905297a4",
    "bounceable_address": "EQBX6K9aXVl3nXINCyPPL86C4ONVmQ8vK360u6dykFKXpHCa",
    "unbounceable_address": "UQBX6K9aXVl3nXINCyPPL86C4ONVmQ8vK360u6dykFKXpC1f"
  },
  ...
]
```

#### 7. Ton Balance in USD
```bash
// GET /balance/ton_usd/{wallet_id}
curl -X 'GET' \
  'http://127.0.0.1:8000/balance/ton_usd/<wallet_id>' \
  -H 'accept: application/json'
```
```json
{
  "balance_usd": 119.70167556455999
}
```

#### 8. Jetton Balance in USD
```bash
// GET /balance/ton_usd/{wallet_id}/{jetton_id}
curl -X 'GET' \
  'http://127.0.0.1:8000/balance/ton_usd/<wallet_id>/{jetton_id}' \
  -H 'accept: application/json'
```
```json
{
  "balance_usd": 119.70167556455999
}
```

#### 9. User CRUD
```text
* POST /user - Create User
* POST /user/{chat_id}/wallets - Add wallet
* DELETE /user/{chat_id}/wallets - Remove wallet
* GET /user/{chat_id}
* DELETE /user/{chat_id}
```

#### 10. Portfolio Asset Distribution [Unreleased]
**Endpoint:**  
`GET /portfolio/get_asset_distribution/{wallet_id}`

**Description:**  
Calculates the distribution of portfolio assets in percentage terms.

**Requires:**  
- Current balances of all assets.
- Current prices of assets in USD.

---

#### 11. Profit and Loss (P/L) [Unreleased]
**Endpoint:**  
`GET /portfolio/profit_and_losses/{wallet_id}`

**Description:**  
Provides the realized and unrealized profit and loss for the portfolio.

**Requires:**  
- Current balances of all assets.
- Current prices of assets in USD.
- Purchase and sale prices.

---

#### 12. Return on Investment (ROI) [Unreleased]
**Endpoint:**  
`GET /portfolio/ROI/{wallet_id}`

**Description:**  
Calculates the return on investment as a percentage relative to the invested capital.

**Requires:**  
- Total invested capital.
- Current portfolio value.

---

#### 13. Average Buy Price [Unreleased]
**Endpoint:**  
- `GET /portfolio/AVG_BUY_PRICE/ton/{wallet_id}`  
- `GET /portfolio/AVG_BUY_PRICE/jetton/{wallet_id}/{jetton_id}`

**Description:**  
Calculates the average buy price for TON or a specific jetton.

**Requires:**  
- Purchase prices and amounts for the specified asset.

---

#### 14. Average Sell Price [Unreleased]
**Endpoint:**  
- `GET /portfolio/AVG_SELL_PRICE/ton/{wallet_id}`  
- `GET /portfolio/AVG_SELL_PRICE/jetton/{wallet_id}/{jetton_id}`

**Description:**  
Calculates the average sell price for TON or a specific jetton.

**Requires:**  
- Sale prices and amounts for the specified asset.

---

#### 15. Average Retention Period [Unreleased]
**Endpoint:**  
`GET /portfolio/get_retention_period/{wallet_id}`

**Description:**  
Calculates the average holding period for assets in the portfolio.

**Requires:**  
- Dates of purchase and sale for each asset.

---

#### 16. Portfolio Summary [Unreleased]
**Endpoint:**  
`GET /portfolio/summary/{wallet_id}`

**Description:**  
Provides a summary of the portfolio, including total value, asset distribution, and performance metrics.

**Requires:**  
- Current balances and prices of all assets.
- Historical transaction data.

---

#### 17. Historical Portfolio Value [Unreleased]
**Endpoint:**  
`GET /portfolio/historical_value/{wallet_id}`

**Description:**  
Shows the historical value of the portfolio over a specified period.

**Requires:**  
- Historical balances of all assets.
- Historical prices of assets.

---

#### 18. Volatility [Unreleased]
**Endpoint:**   
`GET /portfolio/volatility/{wallet_id}`

**Description:**  
Measures the volatility (standard deviation of returns) of the portfolio over time.

**Requires:**  
- Historical returns of the portfolio.

---

#### 19. Asset Correlation [Unreleased]
**Endpoint:**  
`GET /portfolio/asset_correlation/{wallet_id}`

**Description:**  
Calculates the correlation matrix for the returns of assets in the portfolio.

**Requires:**  
- Historical returns of all assets.

### 20. Maximum Drawdown [Unreleased]
**Endpoint:**  
`GET /portfolio/max_drawdown/{wallet_id}`

**Description:**  
Calculates the maximum observed loss from a portfolio peak to a trough before a new peak is reached.

**Requires:**  
- Historical portfolio values.

---

### 21. Value at Risk (VaR) [Unreleased]
**Endpoint:**  
`GET /portfolio/var/{wallet_id}`

**Description:**  
Estimates the potential maximum loss of the portfolio over a specified period at a given confidence level.

**Requires:**  
- Historical portfolio returns.

---

### 22. Sharpe Ratio [Unreleased]
**Endpoint:**  
`GET /portfolio/sharpe_ratio/{wallet_id}`

**Description:**  
Measures the risk-adjusted return of the portfolio using the risk-free rate as a benchmark.

**Requires:**  
- Historical portfolio returns.
- Risk-free rate.

---

### 23. Asset Liquidity Risk [Unreleased]
**Endpoint:**  
`GET /portfolio/liquidity_risk/{wallet_id}`

**Description:**  
Evaluates the difficulty of converting assets into cash without significantly affecting their market price.

**Requires:**  
- Trading volume and market depth for each asset.

---

### 24. Income from Staking or Rewards [Unreleased]
**Endpoint:**  
`GET /portfolio/income/{wallet_id}`

**Description:**  
Summarizes the income generated by the portfolio from staking, dividends, or interest.

**Requires:**  
- Records of income events (e.g., staking rewards).

---

### 25. Currency Exposure [Unreleased]
**Endpoint:**  
`GET /portfolio/currency_exposure/{wallet_id}`

**Description:**  
Breaks down the portfolio by the currencies in which the assets are denominated.

**Requires:**  
- Current balances and their corresponding currencies.

---

### 26. ESG Metrics [Unreleased]
**Endpoint:**  
`GET /portfolio/esg_metrics/{wallet_id}`

**Description:**  
Evaluates the portfolio based on environmental, social, and governance (ESG) criteria.

**Requires:**  
- ESG ratings or scores for each asset.

---

### 27. Tax Obligations [Unreleased]
**Endpoint:**  
`GET /portfolio/tax_obligations/{wallet_id}`

**Description:**  
Estimates the potential tax obligations based on realized gains and local tax laws.

**Requires:**  
- Realized profit and loss data.
- Applicable tax rates.

---

### 28. Best and Worst Performing Assets [Unreleased]
**Endpoint:**  
`GET /portfolio/best_worst_performance/{wallet_id}`

**Description:**  
Identifies the top-performing and worst-performing assets in the portfolio.

**Requires:**  
- Historical and current returns for all assets.

---

### 29. Commission Analysis [Unreleased]
**Endpoint:**  
`GET /portfolio/commission_analysis/{wallet_id}`

**Description:**  
Analyzes the total commissions paid for transactions, including trading, network, and platform fees.

**Requires:**  
- Records of all transaction fees.

---

### 30. Customizable Reports [Unreleased]
**Endpoint:**  
`GET /portfolio/custom_report/{wallet_id}`

**Description:**  
Generates tailored reports based on selected metrics and preferences.

**Requires:**  
- All available portfolio data for selected metrics.
