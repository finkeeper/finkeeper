# 🚀 FinKeeper Backend API

**FinKeeper Backend** is a high-performance API hub built with **FastAPI**, acting as an intermediary between the **web application** and the **AI agent**. While FastAPI processes incoming requests and routes them accordingly, most operations are executed on a **Node.js server** for optimal performance.

📌 **API Documentation:** [Swagger UI](https://api2.finkeeper.pro:8443/docs#/)

---

## ⚡ Core Functionality
- **Handles AI-driven financial interactions** via Atoma LLM.
- **Manages SUI blockchain operations** (wallet creation, balance retrieval, token transfers).
- **Integrates with Navi Protocol** for decentralized finance (DeFi) features like staking and yield optimization.

---

## 🔗 API Endpoints
### **AI & Chat Processing**
- `POST /chat` – Processes user queries via **Atoma LLM**, identifying commands for the AI agent.

### **SUI Blockchain Operations**
- `GET /node/balance/{address}` – Retrieves the **SUI wallet balance** for a given address.
- `POST /node/transfer/` – Transfers **SUI tokens** from the AI agent’s wallet to a specified wallet.
- `POST /node/create_wallet/` – Generates a **mnemonic phrase, wallet address, public and private keys** for AI agent activation.

### **Navi Protocol (DeFi Lending & Staking)**
- `GET /navi/pool/{token_name}` – Fetches **pool information** (APY, rewards, lending rates, etc.) for a given token.
- `POST /navi/deposit` – Deposits **tokens into Navi Protocol** for yield farming and lending.
- `POST /navi/withdraw` – Withdraws **assets from Navi Protocol** back to the AI agent’s wallet.

---

## 🔮 Future Enhancements
✅ Support for **multi-chain portfolio management** (ETH, BSC, TON, SUI).  
✅ AI-powered **yield farming recommendations** tailored to user portfolios.  
✅ Integration of **Move-based smart contracts** for AI-driven transactions.

---

📩 **For issues and contributions, open a GitHub issue.**

