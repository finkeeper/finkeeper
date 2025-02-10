import { Ed25519Keypair } from "@mysten/sui/keypairs/ed25519";
import { SuiClient, CoinStruct } from "@mysten/sui/client";
import { SwapOptions } from "../../types";
import { CoinInfo } from "../../types";
export declare class AccountManager {
    keypair: Ed25519Keypair;
    client: SuiClient;
    address: string;
    /**
     * AccountManager class for managing user accounts.
     */
    constructor({ mnemonic, network, accountIndex, privateKey }?: {
        mnemonic?: string | undefined;
        network?: string | undefined;
        accountIndex?: number | undefined;
        privateKey?: string | undefined;
    });
    /**
     * Returns the derivation path for a given address index.
     *
     * @param addressIndex - The index of the address.
     * @returns The derivation path as a string.
     */
    getDerivationPath(addressIndex: number): string;
    /**
     * Retrieves the public key associated with the account.
     * @returns The public key as a Sui address string.
     */
    getPublicKey(): string;
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
    fetchAllCoins(account: string, cursor?: string | null): Promise<ReadonlyArray<CoinStruct>>;
    /**
     * getAllCoins is an asynchronous function that retrieves all the coins owned by the specified account.
     * It utilizes a recursive function to fetch all pages of coin data if pagination is required.
     *
     * @param prettyPrint - A boolean indicating whether to print the coin data in a formatted manner. Default is true.
     * @returns A Promise that resolves to an array containing all the coins owned by the account.
     */
    getAllCoins(prettyPrint?: boolean): Promise<ReadonlyArray<CoinStruct>>;
    /**
     * getWalletBalance is an asynchronous function that retrieves the balance of all coins in the wallet.
     *
     * @param prettyPrint - A boolean indicating whether to print the data in a pretty format. Default is false.
     * @returns A Promise that resolves to an object containing the balance of each coin in the wallet. Record<string, number>
     */
    getWalletBalance(prettyPrint?: boolean): Promise<Record<string, number>>;
    /**
     * fetchCoins is a helper function that recursively retrieves coin objects for the given account and coin type.
     * It handles pagination by utilizing the cursor provided in the response.
     *
     * @param account - The account address to retrieve coin data for.
     * @param coinType - The coin type to retrieve.
     * @param cursor - An optional cursor for pagination. Default is null.
     * @returns A Promise that resolves to an array containing all the coin objects of the specified type owned by the account.
     */
    fetchCoins(account: string, coinType: string, cursor?: string | null): Promise<ReadonlyArray<CoinStruct>>;
    /**
     * Retrieves coin objects based on the specified coin type, with pagination handling.
     * Recursively fetches all coin objects if they exceed QUERY_MAX_RESULT_LIMIT_CHECKPOINTS (50).
     *
     * @param coinType - The coin type to retrieve coin objects for. Defaults to "0x2::sui::SUI".
     * @returns A Promise that resolves to the retrieved coin objects.
     */
    getCoins(coinType?: any): Promise<{
        data: CoinStruct[];
    }>;
    /**
     * Creates an account capability.
     * @returns A Promise that resolves to the result of the account creation.
     */
    createAccountCap(): Promise<any>;
    /**
     * Sends coins to multiple recipients.
     *
     * @param coinType - The type of coin to send.
     * @param recipients - An array of recipient addresses.
     * @param amounts - An array of amounts to send to each recipient.
     * @returns A promise that resolves to the result of the transaction.
     * @throws An error if the recipient list contains an empty address string, or if the length of the recipient array is not equal to the length of the amounts array, or if there is insufficient balance for the coin.
     */
    sendCoinsToMany(coinType: any, recipients: string[], amounts: number[]): Promise<any>;
    /**
     * Sends a specified amount of coins to a recipient.
     *
     * @param coinType - The type of coin to send.
     * @param recipient - The address of the recipient.
     * @param amount - The amount of coins to send.
     * @returns A promise that resolves when the coins are sent.
     */
    sendCoin(coinType: any, recipient: string, amount: number): Promise<any>;
    /**
     * Transfers multiple objects to multiple recipients.
     * @param objects - An array of objects to be transferred.
     * @param recipients - An array of recipients for the objects.
     * @returns A promise that resolves with the result of the transfer.
     * @throws An error if the length of objects and recipient arrays are not the same.
     */
    transferObjectsToMany(objects: string[], recipients: string[]): Promise<any>;
    /**
     * Transfers an object to a recipient.
     * @param object - The object to be transferred.
     * @param recipient - The recipient of the object.
     * @returns A promise that resolves when the transfer is complete.
     */
    transferObject(object: string, recipient: string): Promise<any>;
    /**
     * Deposits a specified amount of a given coin type to Navi.
     * @param coinType - The coin type to deposit.
     * @param amount - The amount to deposit.
     * @returns A promise that resolves to the result of the deposit transaction.
     * @throws An error if there is insufficient balance for the coin.
     */
    depositToNavi(coinType: CoinInfo, amount: number): Promise<any>;
    /**
     * Deposits a specified amount of a given coin type to Navi with an account cap address.
     * @param coinType - The coin type to deposit.
     * @param amount - The amount to deposit.
     * @param accountCapAddress - The account cap address.
     * @returns A promise that resolves to the result of the deposit transaction.
     * @throws An error if there is insufficient balance for the coin.
     */
    depositToNaviWithAccountCap(coinType: CoinInfo, amount: number, accountCapAddress: string): Promise<any>;
    /**
     * Withdraws a specified amount of coins.
     * @param coinType - The type of coin to withdraw.
     * @param amount - The amount of coins to withdraw.
     * @param updateOracle - A boolean indicating whether to update the oracle. Default is true. Set to false to save gas.
     * @returns A promise that resolves to the result of the withdrawal.
     */
    withdraw(coinType: CoinInfo, amount: number, updateOracle?: boolean): Promise<any>;
    /**
     * Withdraws a specified amount of coins with an account cap.
     *
     * @param coinType - The type of coin to withdraw.
     * @param withdrawAmount - The amount of coins to withdraw.
     * @param accountCapAddress - The address of the account cap.
     * @param updateOracle - A boolean indicating whether to update the oracle. Default is true. Set to false to save gas.
     * @returns A promise that resolves to the result of the withdrawal.
     */
    withdrawWithAccountCap(coinType: CoinInfo, withdrawAmount: number, accountCapAddress: string, updateOracle?: boolean): Promise<any>;
    /**
     * Borrows a specified amount of a given coin.
     *
     * @param coinType - The type of coin to borrow.
     * @param borrowAmount - The amount of the coin to borrow.
     * @returns A promise that resolves to the result of the borrowing operation.
     */
    borrow(coinType: CoinInfo, borrowAmount: number, updateOracle?: boolean): Promise<any>;
    /**
     * Repays a specified amount of a given coin type.
     *
     * @param coinType - The coin type or coin symbol to repay.
     * @param repayAmount - The amount to repay.
     * @returns A promise that resolves to the result of the repayment transaction.
     * @throws An error if there is insufficient balance for the specified coin.
     */
    repay(coinType: CoinInfo, repayAmount: number): Promise<any>;
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
    liquidate(payCoinType: CoinInfo, liquidationAddress: string, collateralCoinType: CoinInfo, liquidationAmount?: number, updateOracle?: boolean): Promise<any>;
    /**
     * Retrieves the health factor for a given address.
     * @param address - The address for which to retrieve the health factor. Defaults to the instance's address.
     * @returns The health factor as a number.
     */
    getHealthFactor(address?: string, client?: SuiClient): Promise<number>;
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
    getDynamicHealthFactor(userAddress: string, coinType: CoinInfo, estimatedSupply?: number, estimatedBorrow?: number, isIncrease?: boolean): Promise<string>;
    /**
     * Retrieves the decimal value for a given coin type.
     * If the coin type has an address property, it uses that address. Otherwise, it uses the coin type itself.
     *
     * @param coinType - The coin type or coin object.
     * @returns The decimal value of the coin.
     */
    getCoinDecimal(coinType: any): Promise<any>;
    parseResult(msg: any): void;
    /**
     * Retrieves the detailed information of a reserve based on the provided asset ID.
     * @param assetId - The ID of the asset for which to retrieve the reserve details.
     * @returns A Promise that resolves to the parsed result of the reserve details.
     */
    getReservesDetail(assetId: number): Promise<import("@mysten/sui/client").SuiObjectResponse>;
    /**
     * Retrieves the NAVI portfolio for the current account.
     * @param prettyPrint - A boolean indicating whether to print the portfolio in a pretty format. Default is true.
     * @returns A Promise that resolves to a Map containing the borrow and supply balances for each reserve.
     */
    getNAVIPortfolio(address?: string, prettyPrint?: boolean): Promise<Map<string, {
        borrowBalance: number;
        supplyBalance: number;
    }>>;
    /**
     * Claims all available rewards for the specified account.
     * @param updateOracle - A boolean indicating whether to update the oracle. Default is true. Set to false to save gas.
     * @returns PTB result
     */
    claimAllRewards(updateOracle?: boolean): Promise<any>;
    /**
     * Stakes a specified amount of SuitoVoloSui.
     * @param stakeAmount The amount of SuitoVoloSui to stake. Must be greater than 1Sui.
     * @returns PTB result
     */
    stakeSuitoVoloSui(stakeAmount: number): Promise<any>;
    /**
     * Unstakes a specified amount of SUI from VOLO SUI.
     * If no amount is provided, unstakes all available vSUI. Must be greater than 1vSui.
     *
     * @param unstakeAmount - The amount of SUI to unstake. If not provided, all available vSUI will be unstaked.
     * @returns PTB result
     */
    unstakeSuiFromVoloSui(unstakeAmount?: number): Promise<any>;
    /**
     * Updates the Oracle.
     *
     * @returns The result of the transaction submission.
     */
    updateOracle(): Promise<any>;
    swap(fromCoinAddress: string, toCoinAddress: string, amountIn: number | string | bigint, minAmountOut: number, apiKey?: string, swapOptions?: SwapOptions): Promise<any>;
    dryRunSwap(fromCoinAddress: string, toCoinAddress: string, amountIn: number | string | bigint, minAmountOut: number, apiKey?: string, swapOptions?: SwapOptions): Promise<import("@mysten/sui/client").DryRunTransactionBlockResponse>;
}
