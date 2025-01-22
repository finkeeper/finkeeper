# 🌟 **Finkeeper DeDust Onchain Interaction Demo**

A demo page for interacting with DeDust and the TON blockchain, developed as part of the **Finkeeper** project.  
This site showcases key onchain integration capabilities for DeDust operations, including token swaps, liquidity management, and wallet connection via **TonConnect**.

---

## 🛍️ **Product Description**

This demo page provides:

- **Wallet connection** via TonConnect.
- **Token swap interface** within the TON network.
- **Adding liquidity** to DeDust pools.
- **Withdrawing liquidity** from pools.

This tool is designed to demonstrate the features and simplify integration with DeDust.

---

## ⚙️ **Technical Description**

### Technologies Used:

- **HTML**, **CSS**, **JavaScript** — the foundation of the interface.
- **Webpack** — for project bundling and dependency management.

### How to Use the Project:

1. **Install dependencies**:
   ```bash
   npm install
   ```
2. **Build the project:**:
   \
   To create the bundle.js file, run:
   ```bash
   npx webpack
   ```
3. **Build output:**:
   \
   The resulting bundle.js file will be located in the /dist folder. Connect it to your index.html to run the application.

## 📂 **Project Structure**

```plaintext
onchain/
├── src/
│ ├── index.html # Main page
│ ├── styles.css # Interface styles
│ ├── script.js # Main application logic
├── webpack.config.js # Webpack configuration
├── .babelrc # Babel configuration
├── package.json # Project dependencies
└── README.md # Documentation
```
