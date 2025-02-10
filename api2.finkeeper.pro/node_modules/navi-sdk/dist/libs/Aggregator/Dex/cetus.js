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
exports.makeCETUSPTB = makeCETUSPTB;
const config_1 = require("../config");
function makeCETUSPTB(txb, poolId, byAmountIn, coinA, amount, a2b, typeArguments) {
    return __awaiter(this, void 0, void 0, function* () {
        let coinTypeA = typeArguments[0];
        let coinTypeB = typeArguments[1];
        const sqrtPriceLimit = BigInt(a2b ? '4295048016' : '79226673515401279992447579055');
        const [cetusReceiveA, cetusReceiveB, cetusflashReceipt] = txb.moveCall({
            target: `${config_1.AggregatorConfig.cetusPackageId}::pool::flash_swap`,
            arguments: [
                txb.object(config_1.AggregatorConfig.cetusConfigId),
                txb.object(poolId),
                txb.pure.bool(a2b),
                txb.pure.bool(byAmountIn),
                amount,
                txb.pure.u128(sqrtPriceLimit),
                txb.object(config_1.AggregatorConfig.clockAddress)
            ],
            typeArguments: [coinTypeA, coinTypeB]
        });
        txb.moveCall({
            target: `${config_1.AggregatorConfig.cetusPackageId}::pool::swap_pay_amount`,
            arguments: [cetusflashReceipt],
            typeArguments: [coinTypeA, coinTypeB]
        });
        if (a2b) {
            const pay_coin_b = txb.moveCall({
                target: '0x2::balance::zero',
                typeArguments: [coinTypeB]
            });
            txb.moveCall({
                target: `${config_1.AggregatorConfig.cetusPackageId}::pool::repay_flash_swap`,
                arguments: [
                    txb.object(config_1.AggregatorConfig.cetusConfigId),
                    txb.object(poolId),
                    coinA,
                    pay_coin_b,
                    cetusflashReceipt
                ],
                typeArguments: [coinTypeA, coinTypeB]
            });
            const coin_a = txb.moveCall({
                target: `0x2::coin::from_balance`,
                arguments: [cetusReceiveA],
                typeArguments: [coinTypeA]
            });
            const receive_coin_b = txb.moveCall({
                target: `0x2::coin::from_balance`,
                arguments: [cetusReceiveB],
                typeArguments: [coinTypeB]
            });
            return { receiveCoin: receive_coin_b, leftCoin: coin_a };
        }
        const [pay_coin_a] = txb.moveCall({
            target: '0x2::balance::zero',
            typeArguments: [coinTypeA]
        });
        txb.moveCall({
            target: `${config_1.AggregatorConfig.cetusPackageId}::pool::repay_flash_swap`,
            arguments: [
                txb.object(config_1.AggregatorConfig.cetusConfigId),
                txb.object(poolId),
                pay_coin_a,
                coinA,
                cetusflashReceipt
            ],
            typeArguments: [coinTypeA, coinTypeB]
        });
        const leftCoin = txb.moveCall({
            target: `0x2::coin::from_balance`,
            arguments: [cetusReceiveB],
            typeArguments: [coinTypeB]
        });
        const receiveCoin = txb.moveCall({
            target: `0x2::coin::from_balance`,
            arguments: [cetusReceiveA],
            typeArguments: [coinTypeA]
        });
        return { receiveCoin, leftCoin };
    });
}
