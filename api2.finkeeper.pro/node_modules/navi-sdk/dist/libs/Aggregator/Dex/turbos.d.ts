import { Transaction } from "@mysten/sui/transactions";
export declare const MAX_TICK_INDEX = 443636;
export declare const MIN_TICK_INDEX = -443636;
export declare const MAX_TICK_INDEX_X64 = "79226673515401279992447579055";
export declare const MIN_TICK_INDEX_X64 = "4295048016";
export declare function makeTurbosPTB(txb: Transaction, poolId: string, byAmountIn: boolean, coinA: any, amount_in: any, a2b: boolean, typeArguments: any, userAddress: string, contractVersionId: string): Promise<{
    turbosCoinB: {
        $kind: "NestedResult";
        NestedResult: [number, number];
    };
    turbosCoinA: {
        $kind: "NestedResult";
        NestedResult: [number, number];
    };
}>;
