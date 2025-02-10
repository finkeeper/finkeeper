import { Transaction, TransactionResult } from "@mysten/sui/transactions";
export declare function makeBluefinPTB(txb: Transaction, poolId: string, pathTempCoin: any, amount: any, a2b: boolean, typeArguments: string[]): Promise<{
    coinAOut: TransactionResult;
    coinBOut: TransactionResult;
}>;
