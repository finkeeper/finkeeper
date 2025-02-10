import { Transaction } from "@mysten/sui/transactions";
export declare function makeDeepbookPTB(txb: Transaction, poolId: string, coinA: any, amountLimit: any, a2b: boolean, typeArguments: any): Promise<{
    baseCoinOut: {
        $kind: "NestedResult";
        NestedResult: [number, number];
    };
    quoteCoinOut: {
        $kind: "NestedResult";
        NestedResult: [number, number];
    };
}>;
