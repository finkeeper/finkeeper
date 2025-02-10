import { Transaction } from "@mysten/sui/transactions";
export declare function makeVSUIPTB(txb: Transaction, pathTempCoin: any, a2b: boolean): Promise<{
    $kind: "NestedResult";
    NestedResult: [number, number];
}>;
