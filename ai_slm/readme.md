# Crypto Portfolio Analysis Small Language Model (SLM)

This repository contains a Small Language Model (SLM) designed to analyze cryptocurrency portfolios and provide actionable recommendations. The model processes structured JSON data about a user's portfolio, evaluates the holdings, and offers suggestions to optimize investments, such as staking opportunities and DeFi protocol usage.

## Features

- **Token Valuation**: Calculates the total value of the user's cryptocurrency portfolio based on token balances and current prices.
- **SOL Staking Recommendations**: Identifies SOL tokens in the portfolio and suggests staking them in protocols like Solayer for potential annual returns.
- **Stablecoin Opportunities**: Aggregates balances of stablecoins (USDT, USDC) and proposes using them in DeFi protocols such as Kamino and Save to earn annual returns.
- **Custom Rules**: Includes pre-defined rules with formulas for calculating potential earnings from staking and DeFi usage.
- **Example Scenarios**: Provides detailed examples of inputs and outputs for testing and refining the model.

---

## Dataset Structure

The dataset includes rules, scenarios, and recommendations to train and guide the SLM.

### **Rules**
Each rule includes:
- **Condition**: Logic to trigger a specific recommendation based on portfolio data.
- **Response**: English and Russian outputs with detailed suggestions.
- **Formula**: Embedded calculation formulas for earnings (e.g., staking returns or DeFi protocol yields).

#### Example Rule for SOL Staking:
```json
{
  "condition": "any(coin['symbol'] == 'SOL' for coin in coins)",
  "response": "You have SOL tokens. Consider staking them to earn annual returns. At approximately 11% annual returns, you could earn an additional {sol_earned:.2f} SOL, equivalent to {usd_earned:.2f} USD.",
  "response_ru": "У вас есть токены SOL. Рассмотрите возможность стейкинга для получения годовой доходности. Вы можете заработать дополнительно {sol_earned:.2f} SOL, что эквивалентно {usd_earned:.2f} USD."
}
