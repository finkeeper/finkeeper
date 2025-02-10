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
exports.makeBluefinPTB = makeBluefinPTB;
const config_1 = require("../config");
const utils_1 = require("@mysten/sui/utils");
;
function makeBluefinPTB(txb, poolId, pathTempCoin, amount, a2b, typeArguments) {
    return __awaiter(this, void 0, void 0, function* () {
        const coinA = a2b ? pathTempCoin : zeroCoin(txb, typeArguments[0]);
        const coinB = a2b ? zeroCoin(txb, typeArguments[1]) : pathTempCoin;
        const coinAInBalance = coinToBalance(txb, coinA, typeArguments[0]);
        const coinBInBalance = coinToBalance(txb, coinB, typeArguments[1]);
        const sqrtPriceLimit = BigInt(a2b ? '4295048017' : '79226673515401279992447579054');
        const args = [
            txb.object(utils_1.SUI_CLOCK_OBJECT_ID),
            txb.object(config_1.AggregatorConfig.bluefinGlobalConfig),
            txb.object(poolId),
            coinAInBalance,
            coinBInBalance,
            txb.pure.bool(a2b),
            txb.pure.bool(true),
            amount,
            txb.pure.u64(0),
            txb.pure.u128(sqrtPriceLimit.toString())
        ];
        const [coinAOutInBalance, coinBOutInBalance] = txb.moveCall({
            target: `${config_1.AggregatorConfig.bluefinPackageId}::pool::swap`,
            typeArguments: typeArguments,
            arguments: args,
        });
        const coinAOut = balanceToCoin(txb, coinAOutInBalance, typeArguments[0]);
        const coinBOut = balanceToCoin(txb, coinBOutInBalance, typeArguments[1]);
        return {
            coinAOut,
            coinBOut
        };
    });
}
const zeroCoin = (txb, coinType) => {
    return txb.moveCall({
        target: "0x2::coin::zero",
        typeArguments: [coinType]
    });
};
function coinToBalance(txb, coin, coinType) {
    return txb.moveCall({
        target: "0x2::coin::into_balance",
        arguments: [coin],
        typeArguments: [coinType],
    });
}
function balanceToCoin(txb, coin, coinType) {
    return txb.moveCall({
        target: `0x2::coin::from_balance`,
        arguments: [coin],
        typeArguments: [coinType]
    });
}
