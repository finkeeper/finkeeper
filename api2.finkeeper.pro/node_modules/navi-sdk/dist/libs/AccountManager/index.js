"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.AccountManager = void 0;
const ed25519_1 = require("@mysten/sui/keypairs/ed25519");
const client_1 = require("@mysten/sui/client");
const Coins_1 = require("../Coins");
const transactions_1 = require("@mysten/sui/transactions");
const address_1 = require("../../address");
const PTB_1 = require("../PTB");
const CallFunctions_1 = require("../CallFunctions");
const assert_1 = __importDefault(require("assert"));
const PTB_2 = require("../PTB");
class AccountManager {
    /**
     * AccountManager class for managing user accounts.
     */
    constructor({ mnemonic = "", network = "mainnet", accountIndex = 0, privateKey = "" } = {}) {
        this.client = new client_1.SuiClient({ url: (0, client_1.getFullnodeUrl)("mainnet") });
        this.address = "";
        if (privateKey && privateKey !== "") {
            this.keypair = ed25519_1.Ed25519Keypair.fromSecretKey(privateKey);
        }
        else {
            this.keypair = ed25519_1.Ed25519Keypair.deriveKeypair(mnemonic, this.getDerivationPath(accountIndex));
        }
        const validNetworkTypes = ["mainnet", "testnet", "devnet", "localnet"];
        try {
            if (validNetworkTypes.includes(network)) {
                this.client = new client_1.SuiClient({
                    url: (0, client_1.getFullnodeUrl)(network),
                });
            }
            else {
                this.client = new client_1.SuiClient({ url: network });
            }
        }
        catch (e) {
            console.log("Invalid network type or RPC", e);
        }
        this.address = this.keypair.getPublicKey().toSuiAddress();
        (0, PTB_2.registerStructs)();
    }
    /**
     * Returns the derivation path for a given address index.
     *
     * @param addressIndex - The index of the address.
     * @returns The derivation path as a string.
     */
    getDerivationPath(addressIndex) {
        return `m/44'/784'/0'/0'/${addressIndex}'`;
    }
    ;
    /**
     * Retrieves the public key associated with the account.
     * @returns The public key as a Sui address string.
     */
    getPublicKey() {
        return this.keypair.getPublicKey().toSuiAddress();
    }
    /**
     * fetchAllCoins is a helper function that recursively retrieves all coin data for the given account.
     * It handles pagination by utilizing the cursor provided in the response.
     * Recursion is necessary because a single request cannot retrieve all data
     * if the user's data exceeds QUERY_MAX_RESULT_LIMIT_CHECKPOINTS (50).
     *
     * @param account - The account address to retrieve coin data for.
     * @param cursor - An optional cursor for pagination. Default is null.
     * @returns A Promise that resolves to an array containing all the coins owned by the account.
     */
    fetchAllCoins(account_1) {
        return __awaiter(this, arguments, void 0, function* (account, cursor = null) {
            const { data, nextCursor, hasNextPage } = yield this.client.getAllCoins({
                owner: account,
                cursor,
            });
            if (!hasNextPage)
                return data;
            const newData = yield this.fetchAllCoins(account, nextCursor);
            return [...data, ...newData];
        });
    }
    /**
     * getAllCoins is an asynchronous function that retrieves all the coins owned by the specified account.
     * It utilizes a recursive function to fetch all pages of coin data if pagination is required.
     *
     * @param prettyPrint - A boolean indicating whether to print the coin data in a formatted manner. Default is true.
     * @returns A Promise that resolves to an array containing all the coins owned by the account.
     */
    getAllCoins() {
        return __awaiter(this, arguments, void 0, function* (prettyPrint = true) {
            const allData = yield this.fetchAllCoins(this.address);
            if (prettyPrint) {
                allData.forEach(({ coinType, coinObjectId, balance }) => {
                    console.log("Coin Type: ", coinType, "| Obj id: ", coinObjectId, " | Balance: ", balance);
                });
            }
            return allData;
        });
    }
    /**
     * getWalletBalance is an asynchronous function that retrieves the balance of all coins in the wallet.
     *
     * @param prettyPrint - A boolean indicating whether to print the data in a pretty format. Default is false.
     * @returns A Promise that resolves to an object containing the balance of each coin in the wallet. Record<string, number>
     */
    getWalletBalance() {
        return __awaiter(this, arguments, void 0, function* (prettyPrint = true) {
            const allBalances = yield this.client.getAllBalances({ owner: this.address });
            const coinBalances = {};
            for (const { coinType, totalBalance } of allBalances) {
                const decimal = yield this.getCoinDecimal(coinType);
                coinBalances[coinType] = Number(totalBalance) / Math.pow(10, decimal);
            }
            if (prettyPrint) {
                Object.entries(coinBalances).forEach(([coinType, balance]) => {
                    const coinName = address_1.AddressMap[coinType] ? `Coin Type: ${address_1.AddressMap[coinType]}` : `Unknown Coin Type: ${coinType}`;
                    console.log(coinName, "| Balance: ", balance);
                });
            }
            return coinBalances;
        });
    }
    /**
     * fetchCoins is a helper function that recursively retrieves coin objects for the given account and coin type.
     * It handles pagination by utilizing the cursor provided in the response.
     *
     * @param account - The account address to retrieve coin data for.
     * @param coinType - The coin type to retrieve.
     * @param cursor - An optional cursor for pagination. Default is null.
     * @returns A Promise that resolves to an array containing all the coin objects of the specified type owned by the account.
     */
    fetchCoins(account_1, coinType_1) {
        return __awaiter(this, arguments, void 0, function* (account, coinType, cursor = null) {
            const { data, nextCursor, hasNextPage } = yield this.client.getCoins({
                owner: account,
                coinType,
                cursor,
            });
            if (!hasNextPage)
                return data;
            const newData = yield this.fetchCoins(account, coinType, nextCursor);
            return [...data, ...newData];
        });
    }
    /**
     * Retrieves coin objects based on the specified coin type, with pagination handling.
     * Recursively fetches all coin objects if they exceed QUERY_MAX_RESULT_LIMIT_CHECKPOINTS (50).
     *
     * @param coinType - The coin type to retrieve coin objects for. Defaults to "0x2::sui::SUI".
     * @returns A Promise that resolves to the retrieved coin objects.
     */
    getCoins() {
        return __awaiter(this, arguments, void 0, function* (coinType = "0x2::sui::SUI") {
            const coinAddress = coinType.address ? coinType.address : coinType;
            const data = [...(yield this.fetchCoins(this.address, coinAddress))];
            return { data };
        });
    }
    /**
     * Creates an account capability.
     * @returns A Promise that resolves to the result of the account creation.
     */
    createAccountCap() {
        return __awaiter(this, void 0, void 0, function* () {
            let txb = new transactions_1.Transaction();
            let sender = this.getPublicKey();
            txb.setSender(sender);
            const config = yield (0, address_1.getConfig)();
            const [ret] = txb.moveCall({
                target: `${config.ProtocolPackage}::lending::create_account`,
            });
            txb.transferObjects([ret], this.getPublicKey());
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    /**
     * Sends coins to multiple recipients.
     *
     * @param coinType - The type of coin to send.
     * @param recipients - An array of recipient addresses.
     * @param amounts - An array of amounts to send to each recipient.
     * @returns A promise that resolves to the result of the transaction.
     * @throws An error if the recipient list contains an empty address string, or if the length of the recipient array is not equal to the length of the amounts array, or if there is insufficient balance for the coin.
     */
    sendCoinsToMany(coinType, recipients, amounts) {
        return __awaiter(this, void 0, void 0, function* () {
            const coinAddress = coinType.address ? coinType.address : coinType;
            // Check if any recipient address is an empty string
            if (recipients.some(address => address.trim() === "")) {
                throw new Error("Recipient list contains an empty address string.");
            }
            if (recipients.length !== amounts.length) {
                throw new Error("recipients.length !== amounts.length");
            }
            let sender = this.getPublicKey();
            const coinBalance = yield (0, Coins_1.getCoinAmount)(this.client, this.getPublicKey(), coinAddress);
            if (coinBalance > 0 &&
                coinBalance >= amounts.reduce((a, b) => a + b, 0)) {
                const txb = new transactions_1.Transaction();
                txb.setSender(sender);
                let coinInfo = yield this.getCoins(coinAddress);
                let coins;
                if (coinAddress == "0x2::sui::SUI") {
                    coins = txb.splitCoins(txb.gas, amounts);
                }
                else {
                    if (coinInfo.data.length >= 2) {
                        let baseObj = coinInfo.data[0].coinObjectId;
                        let allList = coinInfo.data.slice(1).map(coin => coin.coinObjectId);
                        txb.mergeCoins(baseObj, allList);
                    }
                    let mergedCoin = txb.object(coinInfo.data[0].coinObjectId);
                    coins = txb.splitCoins(mergedCoin, amounts);
                }
                recipients.forEach((address, index) => {
                    txb.transferObjects([coins[index]], address);
                });
                const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
                return result;
            }
            else {
                throw new Error("Insufficient balance for this Coin");
            }
        });
    }
    /**
     * Sends a specified amount of coins to a recipient.
     *
     * @param coinType - The type of coin to send.
     * @param recipient - The address of the recipient.
     * @param amount - The amount of coins to send.
     * @returns A promise that resolves when the coins are sent.
     */
    sendCoin(coinType, recipient, amount) {
        return __awaiter(this, void 0, void 0, function* () {
            const coinAddress = coinType.address ? coinType.address : coinType;
            return yield this.sendCoinsToMany(coinAddress, [recipient], [amount]);
        });
    }
    /**
     * Transfers multiple objects to multiple recipients.
     * @param objects - An array of objects to be transferred.
     * @param recipients - An array of recipients for the objects.
     * @returns A promise that resolves with the result of the transfer.
     * @throws An error if the length of objects and recipient arrays are not the same.
     */
    transferObjectsToMany(objects, recipients) {
        return __awaiter(this, void 0, void 0, function* () {
            if (objects.length !== recipients.length) {
                throw new Error("The length of objects and recipients should be the same");
            }
            else {
                let sender = this.getPublicKey();
                const txb = new transactions_1.Transaction();
                txb.setSender(sender);
                objects.forEach((object, index) => {
                    txb.transferObjects([txb.object(object)], recipients[index]);
                });
                const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
                return result;
            }
        });
    }
    /**
     * Transfers an object to a recipient.
     * @param object - The object to be transferred.
     * @param recipient - The recipient of the object.
     * @returns A promise that resolves when the transfer is complete.
     */
    transferObject(object, recipient) {
        return __awaiter(this, void 0, void 0, function* () {
            return yield this.transferObjectsToMany([object], [recipient]);
        });
    }
    /**
     * Deposits a specified amount of a given coin type to Navi.
     * @param coinType - The coin type to deposit.
     * @param amount - The amount to deposit.
     * @returns A promise that resolves to the result of the deposit transaction.
     * @throws An error if there is insufficient balance for the coin.
     */
    depositToNavi(coinType, amount) {
        return __awaiter(this, void 0, void 0, function* () {
            const coinSymbol = coinType.symbol;
            let txb = new transactions_1.Transaction();
            let sender = this.getPublicKey();
            txb.setSender(sender);
            const poolConfig = address_1.pool[coinSymbol];
            let coinInfo = yield this.getCoins(coinType.address);
            if (!coinInfo.data[0]) {
                throw new Error("Insufficient balance for this Coin");
            }
            if (coinSymbol == "Sui") {
                const [toDeposit] = txb.splitCoins(txb.gas, [amount]);
                yield (0, PTB_1.depositCoin)(txb, poolConfig, toDeposit, amount);
            }
            else {
                const mergedCoinObject = (0, PTB_1.returnMergedCoins)(txb, coinInfo);
                const mergedCoinObjectWithAmount = txb.splitCoins(mergedCoinObject, [
                    amount,
                ]);
                yield (0, PTB_1.depositCoin)(txb, poolConfig, mergedCoinObjectWithAmount, amount);
            }
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    /**
     * Deposits a specified amount of a given coin type to Navi with an account cap address.
     * @param coinType - The coin type to deposit.
     * @param amount - The amount to deposit.
     * @param accountCapAddress - The account cap address.
     * @returns A promise that resolves to the result of the deposit transaction.
     * @throws An error if there is insufficient balance for the coin.
     */
    depositToNaviWithAccountCap(coinType, amount, accountCapAddress) {
        return __awaiter(this, void 0, void 0, function* () {
            const coinSymbol = coinType.symbol ? coinType.symbol : coinType;
            let txb = new transactions_1.Transaction();
            let sender = this.getPublicKey();
            txb.setSender(sender);
            const poolConfig = address_1.pool[coinSymbol];
            let coinInfo = yield this.getCoins(coinType.address);
            if (!coinInfo.data[0]) {
                throw new Error("Insufficient balance for this Coin");
            }
            if (coinSymbol == "Sui") {
                const [toDeposit] = txb.splitCoins(txb.gas, [amount]);
                yield (0, PTB_1.depositCoinWithAccountCap)(txb, poolConfig, toDeposit, accountCapAddress);
            }
            else {
                const mergedCoinObject = (0, PTB_1.returnMergedCoins)(txb, coinInfo);
                const mergedCoinObjectWithAmount = txb.splitCoins(mergedCoinObject, [
                    amount,
                ]);
                yield (0, PTB_1.depositCoinWithAccountCap)(txb, poolConfig, mergedCoinObjectWithAmount, accountCapAddress);
            }
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    /**
     * Withdraws a specified amount of coins.
     * @param coinType - The type of coin to withdraw.
     * @param amount - The amount of coins to withdraw.
     * @param updateOracle - A boolean indicating whether to update the oracle. Default is true. Set to false to save gas.
     * @returns A promise that resolves to the result of the withdrawal.
     */
    withdraw(coinType_1, amount_1) {
        return __awaiter(this, arguments, void 0, function* (coinType, amount, updateOracle = true) {
            const coinSymbol = coinType.symbol ? coinType.symbol : coinType;
            let txb = new transactions_1.Transaction();
            if (updateOracle) {
                yield (0, PTB_1.updateOraclePTB)(this.client, txb);
            }
            let sender = this.getPublicKey();
            txb.setSender(sender);
            const poolConfig = address_1.pool[coinSymbol];
            const [returnCoin] = yield (0, PTB_1.withdrawCoin)(txb, poolConfig, amount);
            txb.transferObjects([returnCoin], sender);
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    /**
     * Withdraws a specified amount of coins with an account cap.
     *
     * @param coinType - The type of coin to withdraw.
     * @param withdrawAmount - The amount of coins to withdraw.
     * @param accountCapAddress - The address of the account cap.
     * @param updateOracle - A boolean indicating whether to update the oracle. Default is true. Set to false to save gas.
     * @returns A promise that resolves to the result of the withdrawal.
     */
    withdrawWithAccountCap(coinType_1, withdrawAmount_1, accountCapAddress_1) {
        return __awaiter(this, arguments, void 0, function* (coinType, withdrawAmount, accountCapAddress, updateOracle = true) {
            const coinSymbol = coinType.symbol ? coinType.symbol : coinType;
            let txb = new transactions_1.Transaction();
            if (updateOracle) {
                yield (0, PTB_1.updateOraclePTB)(this.client, txb);
            }
            let sender = this.getPublicKey();
            txb.setSender(sender);
            const poolConfig = address_1.pool[coinSymbol];
            const [returnCoin] = yield (0, PTB_1.withdrawCoinWithAccountCap)(txb, poolConfig, accountCapAddress, withdrawAmount, sender);
            txb.transferObjects([returnCoin], sender);
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    /**
     * Borrows a specified amount of a given coin.
     *
     * @param coinType - The type of coin to borrow.
     * @param borrowAmount - The amount of the coin to borrow.
     * @returns A promise that resolves to the result of the borrowing operation.
     */
    borrow(coinType_1, borrowAmount_1) {
        return __awaiter(this, arguments, void 0, function* (coinType, borrowAmount, updateOracle = true) {
            const coinSymbol = coinType.symbol ? coinType.symbol : coinType;
            let txb = new transactions_1.Transaction();
            if (updateOracle) {
                yield (0, PTB_1.updateOraclePTB)(this.client, txb);
            }
            let sender = this.getPublicKey();
            txb.setSender(sender);
            const poolConfig = address_1.pool[coinSymbol];
            const [returnCoin] = yield (0, PTB_1.borrowCoin)(txb, poolConfig, borrowAmount);
            txb.transferObjects([returnCoin], sender);
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    /**
     * Repays a specified amount of a given coin type.
     *
     * @param coinType - The coin type or coin symbol to repay.
     * @param repayAmount - The amount to repay.
     * @returns A promise that resolves to the result of the repayment transaction.
     * @throws An error if there is insufficient balance for the specified coin.
     */
    repay(coinType, repayAmount) {
        return __awaiter(this, void 0, void 0, function* () {
            const coinSymbol = coinType.symbol ? coinType.symbol : coinType;
            let txb = new transactions_1.Transaction();
            let sender = this.getPublicKey();
            txb.setSender(sender);
            const poolConfig = address_1.pool[coinSymbol];
            let coinInfo = yield this.getCoins(coinType.address);
            if (!coinInfo.data[0]) {
                throw new Error("Insufficient balance for this Coin");
            }
            if (coinSymbol == "Sui") {
                const [toDeposit] = txb.splitCoins(txb.gas, [repayAmount]);
                yield (0, PTB_1.repayDebt)(txb, poolConfig, toDeposit, repayAmount);
            }
            else {
                const mergedCoinObject = (0, PTB_1.returnMergedCoins)(txb, coinInfo);
                const mergedCoinObjectWithAmount = txb.splitCoins(mergedCoinObject, [
                    repayAmount,
                ]);
                yield (0, PTB_1.repayDebt)(txb, poolConfig, mergedCoinObjectWithAmount, repayAmount);
            }
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    /**
     * Liquidates a specified amount of coins.
     *
     * @param payCoinType - The coin type to be paid for liquidation.
     * @param liquidationAddress - The address to which the liquidated coins will be transferred.
     * @param collateralCoinType - The coin type to be used as collateral for liquidation.
     * @param liquidationAmount - The amount of coins to be liquidated (optional, default is 0).
     * @param updateOracle - A boolean indicating whether to update the oracle. Default is true. Set to false to save gas.
     * @returns PtbResult - The result of the liquidation transaction.
     */
    liquidate(payCoinType_1, liquidationAddress_1, collateralCoinType_1) {
        return __awaiter(this, arguments, void 0, function* (payCoinType, liquidationAddress, collateralCoinType, liquidationAmount = 0, updateOracle = true) {
            let txb = new transactions_1.Transaction();
            if (updateOracle) {
                yield (0, PTB_1.updateOraclePTB)(this.client, txb);
            }
            txb.setSender(this.address);
            let coinInfo = yield this.getCoins(payCoinType.address);
            let allBalance = yield this.client.getBalance({ owner: this.address, coinType: payCoinType.address });
            let { totalBalance } = allBalance;
            if (liquidationAmount != 0) {
                (0, assert_1.default)(liquidationAmount * Math.pow(10, payCoinType.decimal) <= Number(totalBalance), "Insufficient balance for this Coin, please don't apply decimals to liquidationAmount");
                totalBalance = (liquidationAmount * Math.pow(10, payCoinType.decimal)).toString();
            }
            if (payCoinType.symbol == "Sui") {
                totalBalance = (Number(totalBalance) - 1 * 1e9).toString(); //You need to keep some Sui for gas
                let [mergedCoin] = txb.splitCoins(txb.gas, [txb.pure.u64(Number(totalBalance))]);
                const [mergedCoinBalance] = txb.moveCall({
                    target: `0x2::coin::into_balance`,
                    arguments: [mergedCoin],
                    typeArguments: [payCoinType.address],
                });
                const [collateralBalance, remainingDebtBalance] = yield (0, PTB_1.liquidateFunction)(txb, payCoinType, mergedCoinBalance, collateralCoinType, liquidationAddress, totalBalance);
                const [collateralCoin] = txb.moveCall({
                    target: `0x2::coin::from_balance`,
                    arguments: [collateralBalance],
                    typeArguments: [collateralCoinType.address],
                });
                const [leftDebtCoin] = txb.moveCall({
                    target: `0x2::coin::from_balance`,
                    arguments: [remainingDebtBalance],
                    typeArguments: [payCoinType.address],
                });
                txb.transferObjects([collateralCoin, leftDebtCoin], this.address);
            }
            else {
                if (coinInfo.data.length >= 2) {
                    const txbMerge = new transactions_1.Transaction();
                    txbMerge.setSender(this.address);
                    let baseObj = coinInfo.data[0].coinObjectId;
                    let allList = coinInfo.data.slice(1).map(coin => coin.coinObjectId);
                    txb.mergeCoins(baseObj, allList);
                    (0, PTB_1.SignAndSubmitTXB)(txbMerge, this.client, this.keypair);
                }
                let mergedCoin = txb.object(coinInfo.data[0].coinObjectId);
                const [collateralCoinBalance] = txb.moveCall({
                    target: `0x2::coin::into_balance`,
                    arguments: [mergedCoin],
                    typeArguments: [payCoinType.address],
                });
                const [collateralBalance, remainingDebtBalance] = yield (0, PTB_1.liquidateFunction)(txb, payCoinType, collateralCoinBalance, collateralCoinType, liquidationAddress, totalBalance);
                const [collateralCoin] = txb.moveCall({
                    target: `0x2::coin::from_balance`,
                    arguments: [collateralBalance],
                    typeArguments: [collateralCoinType.address],
                });
                const [leftDebtCoin] = txb.moveCall({
                    target: `0x2::coin::from_balance`,
                    arguments: [remainingDebtBalance],
                    typeArguments: [payCoinType.address],
                });
                txb.transferObjects([collateralCoin, leftDebtCoin], this.address);
            }
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    /**
     * Retrieves the health factor for a given address.
     * @param address - The address for which to retrieve the health factor. Defaults to the instance's address.
     * @returns The health factor as a number.
     */
    getHealthFactor() {
        return __awaiter(this, arguments, void 0, function* (address = this.address, client) {
            const result = yield (0, CallFunctions_1.getHealthFactorCall)(address, client ? client : this.client);
            const healthFactor = Number(result[0]) / Math.pow(10, 27);
            return healthFactor;
        });
    }
    /**
     * Retrieves the dynamic health factor for a given user in a specific pool.
     * @param userAddress - The address of the user.
     * @param poolName - The name of the pool.
     * @param estimatedSupply - The estimated supply value (default: 0).
     * @param estimatedBorrow - The estimated borrow value (default: 0).
     * @param isIncrease - A boolean indicating whether the estimated supply or borrow is increasing (default: true).
     * @returns The health factor for the user in the pool.
     * @throws Error if the pool does not exist.
     */
    getDynamicHealthFactor(userAddress_1, coinType_1) {
        return __awaiter(this, arguments, void 0, function* (userAddress, coinType, estimatedSupply = 0, estimatedBorrow = 0, isIncrease = true) {
            const poolConfig = address_1.pool[coinType.symbol];
            if (!poolConfig) {
                throw new Error("Pool does not exist");
            }
            const config = yield (0, address_1.getConfig)();
            const tx = new transactions_1.Transaction();
            const result = yield (0, CallFunctions_1.moveInspect)(tx, this.client, this.getPublicKey(), `${config.ProtocolPackage}::dynamic_calculator::dynamic_health_factor`, [
                tx.object('0x06'), // clock object id
                tx.object(config.StorageId), // object id of storage
                tx.object(config.PriceOracle), // object id of price oracle
                tx.object(poolConfig.poolId),
                tx.pure.address(userAddress), // user address,
                tx.pure.u8(poolConfig.assetId),
                tx.pure.u64(estimatedSupply),
                tx.pure.u64(estimatedBorrow),
                tx.pure.bool(isIncrease)
            ], [poolConfig.type]);
            const healthFactor = Number(result[0]) / Math.pow(10, 27);
            if (estimatedSupply > 0) {
                console.log('With EstimateSupply Change: ', `${estimatedSupply}`, ' address: ', `${userAddress}`, ' health factor is: ', healthFactor.toString());
            }
            else if (estimatedBorrow > 0) {
                console.log('With EstimateBorrow Change: ', `${estimatedBorrow}`, ' address: ', `${userAddress}`, ' health factor is: ', healthFactor.toString());
            }
            else {
                console.log('address: ', `${userAddress}`, ' health factor is: ', healthFactor.toString());
            }
            return healthFactor.toString();
        });
    }
    /**
     * Retrieves the decimal value for a given coin type.
     * If the coin type has an address property, it uses that address. Otherwise, it uses the coin type itself.
     *
     * @param coinType - The coin type or coin object.
     * @returns The decimal value of the coin.
     */
    getCoinDecimal(coinType) {
        return __awaiter(this, void 0, void 0, function* () {
            const coinAddress = coinType.address ? coinType.address : coinType;
            const decimal = yield (0, Coins_1.getCoinDecimal)(this.client, coinAddress);
            return decimal;
        });
    }
    parseResult(msg) {
        console.log(JSON.stringify(msg, null, 2));
    }
    /**
     * Retrieves the detailed information of a reserve based on the provided asset ID.
     * @param assetId - The ID of the asset for which to retrieve the reserve details.
     * @returns A Promise that resolves to the parsed result of the reserve details.
     */
    getReservesDetail(assetId) {
        return __awaiter(this, void 0, void 0, function* () {
            return (0, CallFunctions_1.getReservesDetail)(assetId, this.client);
        });
    }
    /**
     * Retrieves the NAVI portfolio for the current account.
     * @param prettyPrint - A boolean indicating whether to print the portfolio in a pretty format. Default is true.
     * @returns A Promise that resolves to a Map containing the borrow and supply balances for each reserve.
     */
    getNAVIPortfolio() {
        return __awaiter(this, arguments, void 0, function* (address = this.address, prettyPrint = true) {
            return (0, CallFunctions_1.getAddressPortfolio)(address, prettyPrint, this.client);
        });
    }
    /**
     * Claims all available rewards for the specified account.
     * @param updateOracle - A boolean indicating whether to update the oracle. Default is true. Set to false to save gas.
     * @returns PTB result
     */
    claimAllRewards() {
        return __awaiter(this, arguments, void 0, function* (updateOracle = true) {
            let txb = yield (0, PTB_1.claimAllRewardsPTB)(this.client, this.address);
            txb.setSender(this.address);
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    /**
     * Stakes a specified amount of SuitoVoloSui.
     * @param stakeAmount The amount of SuitoVoloSui to stake. Must be greater than 1Sui.
     * @returns PTB result
     */
    stakeSuitoVoloSui(stakeAmount) {
        return __awaiter(this, void 0, void 0, function* () {
            let txb = new transactions_1.Transaction();
            txb.setSender(this.address);
            (0, assert_1.default)(stakeAmount >= 1e9, "Stake amount should be greater than 1Sui");
            const [toSwapSui] = txb.splitCoins(txb.gas, [stakeAmount]);
            const vSuiCoin = yield (0, PTB_1.stakeTovSuiPTB)(txb, toSwapSui);
            txb.transferObjects([vSuiCoin], this.address);
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    /**
     * Unstakes a specified amount of SUI from VOLO SUI.
     * If no amount is provided, unstakes all available vSUI. Must be greater than 1vSui.
     *
     * @param unstakeAmount - The amount of SUI to unstake. If not provided, all available vSUI will be unstaked.
     * @returns PTB result
     */
    unstakeSuiFromVoloSui() {
        return __awaiter(this, arguments, void 0, function* (unstakeAmount = -1) {
            let txb = new transactions_1.Transaction();
            txb.setSender(this.address);
            let coinInfo = yield this.getCoins(address_1.vSui.address);
            if (coinInfo.data.length >= 2) {
                const txbMerge = new transactions_1.Transaction();
                txbMerge.setSender(this.address);
                let baseObj = coinInfo.data[0].coinObjectId;
                let allList = coinInfo.data.slice(1).map(coin => coin.coinObjectId);
                txbMerge.mergeCoins(baseObj, allList);
                yield (0, PTB_1.SignAndSubmitTXB)(txbMerge, this.client, this.keypair);
            }
            coinInfo = yield this.getCoins(address_1.vSui.address);
            if (unstakeAmount == -1) {
                unstakeAmount = Number(coinInfo.data[0].balance);
            }
            (0, assert_1.default)(unstakeAmount >= 1e9, "Unstake amount should >= 1vSui");
            let mergedCoin = txb.object(coinInfo.data[0].coinObjectId);
            const [splittedCoin] = txb.splitCoins(mergedCoin, [unstakeAmount]);
            yield (0, PTB_1.unstakeTovSui)(txb, splittedCoin);
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    /**
     * Updates the Oracle.
     *
     * @returns The result of the transaction submission.
     */
    updateOracle() {
        return __awaiter(this, void 0, void 0, function* () {
            let txb = new transactions_1.Transaction();
            txb.setSender(this.address);
            yield (0, PTB_1.updateOraclePTB)(this.client, txb);
            const result = (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    swap(fromCoinAddress_1, toCoinAddress_1, amountIn_1, minAmountOut_1, apiKey_1) {
        return __awaiter(this, arguments, void 0, function* (fromCoinAddress, toCoinAddress, amountIn, minAmountOut, apiKey, swapOptions = { baseUrl: undefined, dexList: [], byAmountIn: true, depth: 3 }) {
            const txb = new transactions_1.Transaction();
            txb.setSender(this.address);
            const coinA = yield (0, PTB_1.getCoinPTB)(this.address, fromCoinAddress, amountIn, txb, this.client);
            const finalCoinB = yield (0, PTB_1.swapPTB)(this.address, txb, fromCoinAddress, toCoinAddress, coinA, amountIn, minAmountOut, apiKey, swapOptions);
            txb.transferObjects([finalCoinB], this.address);
            const result = yield (0, PTB_1.SignAndSubmitTXB)(txb, this.client, this.keypair);
            return result;
        });
    }
    dryRunSwap(fromCoinAddress_1, toCoinAddress_1, amountIn_1, minAmountOut_1, apiKey_1) {
        return __awaiter(this, arguments, void 0, function* (fromCoinAddress, toCoinAddress, amountIn, minAmountOut, apiKey, swapOptions = { baseUrl: undefined, dexList: [], byAmountIn: true, depth: 3 }) {
            const txb = new transactions_1.Transaction();
            txb.setSender(this.address);
            const coinA = yield (0, PTB_1.getCoinPTB)(this.address, fromCoinAddress, amountIn, txb, this.client);
            const finalCoinB = yield (0, PTB_1.swapPTB)(this.address, txb, fromCoinAddress, toCoinAddress, coinA, amountIn, minAmountOut, apiKey, swapOptions);
            txb.transferObjects([finalCoinB], this.address);
            const dryRunTxBytes = yield txb.build({
                client: this.client
            });
            const dryRunResult = yield this.client.dryRunTransactionBlock({ transactionBlock: dryRunTxBytes });
            return dryRunResult;
        });
    }
}
exports.AccountManager = AccountManager;
