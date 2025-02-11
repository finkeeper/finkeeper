
![enter image description here](https://sheremetev.aoserver.ru/storage/8ae7c3e09485cbe7701b2aa305ba9078/Marketing/FinKeeper/FinKeeper-1550-500.png)
# Finkeeper
[FinKeeper](https://finkeeper.pro/app) AI agent & Yield Farming Optimization with portfolio tracker.

Solana AI Hackathon [participant](https://www.solanaaihackathon.com/projects). Semi-final of the [TON Hackathon](https://blog.ton.org/hackers-league-semi-finalists-revealed). FinKeeper received a grant from Stone.fi (No. 1 DEX on TON with TVL over $150 million).

## 🌐 A Modern Crypto Investor's Challenge

A modern crypto investor stores assets across multiple wallets, DeFi platforms, and exchange accounts, which complicates asset management. Additionally, using DeFi is inaccessible for most people due to the high complexity of interfaces and interactions.

## 🚀 FinKeeper: Your Gateway to WEB3

FinKeeper provides a complete financial overview across multiple crypto wallets and exchanges. It serves as a single window into the world of WEB3, offering a simple and intuitive interface. With FinKeeper, users can:

- View a complete overview of their portfolio.
- Set buy and sell goals (notifications available now, automated orders coming soon).

## 🤖 Built-in AI Agent: Your Guide and Friend in WEB3

- Provides tips on coins and answers questions.
- Helps transfer assets (now working transfer from AI wallet)
- Overcomes the complexities of DeFi, ensuring high returns through:  
  - Smart pool selection. (now working with Navi Protocol SDK)
  - Automatic rebalancing between pools. (in roadmap)
  - Auto-compounding. (in roadmap)

## ⚡ **Tech Stack**
- **SUI Blockchain** – Creating and managing wallets, executing transactions, and connecting wallets for portfolio viewing and asset management.
- **ATOMA** – AI processing for text-based queries, prompt analysis, and portfolio evaluation.  
  - **Model used:** `meta-llama/Llama-3.3-70B-Instruct`.  
  - _Future plans:_ Integration of **`multilingual-e5-large-instruct`** for knowledge base management and testing **DeepSeek-R1** for enhanced AI processing.
- **Navi Protocol** – A decentralized lending and borrowing protocol on SUI.  
  - Used for **depositing SUI** to earn yield and **withdrawing funds** from staking pools.  
  - _Future plans_: use for AI-driven suggestions for maximizing returns via DeFi strategies (leverage farming, porfolio rebalancing).
Backend readme: https://github.com/finkeeper/finkeeper/tree/main/api2.finkeeper.pro

- **FastAPI** – A high-performance Python-based API framework for handling multi-chain operations.
- **Uvicorn** – An ASGI server optimized for running FastAPI.
- **Node.js** – Used for blockchain service integrations, real-time event handling, and API communication.
- **Yii2 + MySQL** – The backend stack for the FinKeeper web application, handling user authentication, transaction history, and asset tracking.

## 🔥 **Quick Start for Users**  

### **1️⃣ Verify and Connect Telegram**  
- Go to [https://finkeeper.pro/app](https://finkeeper.pro/app), click **"Verify Yourself"**, and connect your **Telegram account** to log into the application.  
- **Authorization is required to create a wallet.**  
- If you encounter any issues, you can first create an account via the **Telegram bot**: [https://t.me/finkeeper_app_bot?start](https://t.me/finkeeper_app_bot?start).  

---

### **2️⃣ Create an AI Agent Wallet**  
- Click **"Create AI agent wallet"**, and a **SUI blockchain wallet** will be generated.  
- This wallet is linked to the user account (**currently via Telegram authentication**).  
- The AI Agent will use this wallet to execute **on-chain transactions** (*eventually replaced by a Move smart contract*).  

---

### **3️⃣ Fund Your Wallet**  
- You can find your wallet **address** in the **top-right corner** of the interface.  
- Copy the address and **fund it from your personal SUI wallet** or **directly from a crypto exchange**.  
- *Currently, only SUI tokens are displayed. In the future, all SUI assets and DeFi positions will be visible.*  

---

### **4️⃣ Send SUI from the AI Agent Wallet**  
- To **send SUI**, type the command:  
  ```
  send 1 SUI to WALLET_ADDRESS
  ```
- The AI agent will **request confirmation** before executing the transaction.  
- If you confirm by selecting **OK**, the transaction will be sent to the blockchain, and you will receive a **transaction explorer link**.  
- This command can be used to:
  - **Return funds to your personal wallet**  
  - **Send SUI to an exchange**  
  - **Transfer SUI to another user**  
- *Future update: We will add an "Address Book," allowing users to send funds using simpler commands like:*  
  ```
  send 10 SUI to my wallet
  send 100 SUI to mom
  ```

---

### **5️⃣ Deposit Funds into Navi Protocol**  
- To **deposit SUI** into the **Navi Protocol lending platform**, use the command:  
  ```
  deposit 10 SUI to Navi
  ```
- *Future updates: We will support depositing and tracking other assets.*  

---

### **6️⃣ Withdraw Funds from Navi Protocol**  
- To **withdraw SUI** from Navi Protocol, use the command:  
  ```
  withdraw 10 SUI from Navi
  ```

---

### **7️⃣ Portfolio Analysis with Connected Wallets**  
- If you connect your **SUI, Solana, and TON wallets**, along with your **Bybit and OKX exchange accounts**, you can analyze your portfolio using the command:  
  ```
  portfolio analysis
  ```
- *Future update: AI-powered yield farming suggestions tailored to the tokens in your portfolio.*  

---

### **🔹 AI Processing with Atoma LLM**  
- All commands are processed by the **Atoma LLM**.  
- **Even minor variations or synonyms in commands will still work.**  
- The AI will automatically understand and execute transactions based on your input.  

🚀 **Start optimizing your crypto portfolio today!**


 
## WorkFlow
<img src="https://sheremetev.aoserver.ru/storage/8ae7c3e09485cbe7701b2aa305ba9078/Marketing/FinKeeper/WorkFlow.png"  />

# 🛠️ AI integration (development plan)

AI Agent tracks the opinions of influencers, identifies narratives, tracks DeFi protocol rates, analyses market statistics, analyses project github and white paper etc.


## 🧑‍💻 Personal Assistants
- Informing and advising the user
- Automatic order creation when a coin reaches its target price
- On-demand order creation 
- Automated portfolio rebalancing

## ⚙️ DeFi Automation
- Depositing into liquidity pools for earning
- Rebalancing between pools
- Auto-compounding
- Leverage farming automation


### Integrations
SUI, ATOMA, NAVI, BlueFIN, Bybit, OKX, Solana, TON, Ston.fi 

<img src="https://s2.coinmarketcap.com/static/img/coins/64x64/20947.png" height="64" /> <img src="https://sheremetev.aoserver.ru/storage/8ae7c3e09485cbe7701b2aa305ba9078/Marketing/FinKeeper/fin-atoma.png" height="64" /> <img src="https://s2.coinmarketcap.com/static/img/coins/64x64/29296.png" height="64" /> <img src="https://s2.coinmarketcap.com/static/img/coins/64x64/8724.png" height="64" /> <img src="https://s2.coinmarketcap.com/static/img/exchanges/64x64/521.png" width="64" height="64" /> <img src="https://s2.coinmarketcap.com/static/img/exchanges/64x64/294.png" width="64" height="64" /> <img src="https://s2.coinmarketcap.com/static/img/coins/64x64/5426.png" width="64" height="64" /> <img src="https://s2.coinmarketcap.com/static/img/coins/64x64/11419.png" width="64" height="64" /> <img src="https://s2.coinmarketcap.com/static/img/coins/64x64/27311.png" width="64" height="64" />

## Team
![enter image description here](https://sheremetev.aoserver.ru/storage/8ae7c3e09485cbe7701b2aa305ba9078/Marketing/FinKeeper/finkeeper-Team.png)

## Links
-   [FinKeeper](https://finkeeper.pro/app)
-   [X.com (Twitter)](https://x.com/FinKeeper/)
-   [TG channel (EN)](https://t.me/+EcdwEgf0kjVmNjli)
-   [TG channel (RU)](https://t.me/+OvMVn3V9mDRjYzc6)
-   [User docs GitBook EN](https://finkeeper.gitbook.io/finkeeper/en)
-   [User docs GitBook RU](https://finkeeper.gitbook.io/finkeeper)
