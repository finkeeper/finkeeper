import { Transaction } from "@mysten/sui/transactions";
export declare function makeCETUSPTB(txb: Transaction, poolId: string, byAmountIn: boolean, coinA: any, amount: any, a2b: boolean, typeArguments: any): Promise<{
    receiveCoin: any;
    leftCoin: any;
}>;
