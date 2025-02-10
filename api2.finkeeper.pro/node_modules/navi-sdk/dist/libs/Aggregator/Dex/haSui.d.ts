import { Transaction } from "@mysten/sui/transactions";
export declare function makeHASUIPTB(txb: Transaction, pathTempCoin: any, a2b: boolean): Promise<{
    $kind: "NestedResult";
    NestedResult: [number, number];
}>;
