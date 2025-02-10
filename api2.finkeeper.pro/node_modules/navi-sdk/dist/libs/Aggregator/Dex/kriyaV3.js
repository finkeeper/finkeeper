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
Object.defineProperty(exports, "__esModule", { value: true });
exports.makeKriyaV3PTB = makeKriyaV3PTB;
const config_1 = require("../config");
function makeKriyaV3PTB(txb, poolId, byAmountIn, coinA, amount, a2b, typeArguments) {
    return __awaiter(this, void 0, void 0, function* () {
        const sqrtPriceLimit = BigInt(a2b ? '4295048016' : '79226673515401279992447579055');
        const args = [
            txb.object(poolId),
            txb.pure.bool(a2b),
            txb.pure.bool(byAmountIn),
            typeof amount === 'number' ? txb.pure.u64(amount) : amount,
            txb.pure.u128(sqrtPriceLimit),
            txb.object(config_1.AggregatorConfig.clockAddress),
            txb.object(config_1.AggregatorConfig.kriyaV3Version),
        ];
        const [receive_balance_a, receive_balance_b, receipt] = txb.moveCall({
            target: `${config_1.AggregatorConfig.kriyaV3PackageId}::trade::flash_swap`,
            typeArguments: typeArguments,
            arguments: args,
        });
        if (a2b) {
            txb.moveCall({
                target: '0x2::balance::destroy_zero',
                arguments: [receive_balance_a],
                typeArguments: [typeArguments[0]]
            });
            let BalanceA = txb.moveCall({
                target: "0x2::coin::into_balance",
                arguments: [coinA],
                typeArguments: [typeArguments[0]],
            });
            const [BalanceB] = txb.moveCall({
                target: '0x2::balance::zero',
                typeArguments: [typeArguments[1]]
            });
            txb.moveCall({
                target: `${config_1.AggregatorConfig.kriyaV3PackageId}::trade::repay_flash_swap`,
                arguments: [
                    txb.object(poolId),
                    receipt,
                    BalanceA,
                    BalanceB,
                    txb.object(config_1.AggregatorConfig.kriyaV3Version),
                ],
                typeArguments: typeArguments
            });
            const receiveCoin = txb.moveCall({
                target: `0x2::coin::from_balance`,
                arguments: [receive_balance_b],
                typeArguments: [typeArguments[1]]
            });
            return receiveCoin;
        }
        txb.moveCall({
            target: '0x2::balance::destroy_zero',
            arguments: [receive_balance_b],
            typeArguments: [typeArguments[1]]
        });
        let BalanceB = txb.moveCall({
            target: "0x2::coin::into_balance",
            arguments: [coinA],
            typeArguments: [typeArguments[1]],
        });
        const [BalanceA] = txb.moveCall({
            target: '0x2::balance::zero',
            typeArguments: [typeArguments[0]]
        });
        txb.moveCall({
            target: `${config_1.AggregatorConfig.kriyaV3PackageId}::trade::repay_flash_swap`,
            arguments: [
                txb.object(poolId),
                receipt,
                BalanceA,
                BalanceB,
                txb.object(config_1.AggregatorConfig.kriyaV3Version),
            ],
            typeArguments: typeArguments
        });
        const receiveCoin = txb.moveCall({
            target: `0x2::coin::from_balance`,
            arguments: [receive_balance_a],
            typeArguments: [typeArguments[0]]
        });
        return receiveCoin;
    });
}
