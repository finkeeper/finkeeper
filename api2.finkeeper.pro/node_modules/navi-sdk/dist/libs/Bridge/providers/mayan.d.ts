import { SolanaTransactionSigner, JitoBundleOptions, Erc20Permit } from "@mayanfinance/swap-sdk";
import { BridgeSwapQuote } from "../../../types";
import { SuiClient } from "@mysten/sui/client";
import { Transaction } from "@mysten/sui/transactions";
import { Connection, SendOptions } from "@solana/web3.js";
import { Signer, Overrides } from "ethers";
type SuiWalletConnection = {
    provider: SuiClient;
    signTransaction: (data: {
        transaction: Transaction;
    }) => Promise<{
        bytes: string;
        signature: string;
    }>;
};
type SolanaWalletConnection = {
    signTransaction: SolanaTransactionSigner;
    connection: Connection;
    extraRpcs?: string[];
    sendOptions?: SendOptions;
    jitoOptions?: JitoBundleOptions;
};
type EVMWalletConnection = {
    overrides: Overrides | null | undefined;
    signer: Signer;
    permit: Erc20Permit | null | undefined;
    waitForTransaction: (data: {
        hash: string;
        confirmations: number;
    }) => Promise<void>;
};
export type WalletConnection = {
    sui?: SuiWalletConnection;
    solana?: SolanaWalletConnection;
    evm?: EVMWalletConnection;
};
export declare function swap(route: BridgeSwapQuote, fromAddress: string, toAddress: string, walletConnection: WalletConnection, referrerAddresses?: {
    sui?: string;
    evm?: string;
    solana?: string;
}): Promise<string>;
export {};
