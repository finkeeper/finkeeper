import { Transaction } from "@mysten/sui/transactions";
export declare function makeKriyaV2PTB(txb: Transaction, poolId: string, byAmountIn: boolean, coinA: any, amount: any, a2b: boolean, typeArguments: any): Promise<{
    $kind: "NestedResult";
    NestedResult: [number, number];
}>;
