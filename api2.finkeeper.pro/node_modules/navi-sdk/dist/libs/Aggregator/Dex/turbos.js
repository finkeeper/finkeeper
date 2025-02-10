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
exports.MIN_TICK_INDEX_X64 = exports.MAX_TICK_INDEX_X64 = exports.MIN_TICK_INDEX = exports.MAX_TICK_INDEX = void 0;
exports.makeTurbosPTB = makeTurbosPTB;
const config_1 = require("../config");
exports.MAX_TICK_INDEX = 443636;
exports.MIN_TICK_INDEX = -443636;
exports.MAX_TICK_INDEX_X64 = '79226673515401279992447579055';
exports.MIN_TICK_INDEX_X64 = '4295048016';
function makeTurbosPTB(txb, poolId, byAmountIn, coinA, amount_in, a2b, typeArguments, userAddress, contractVersionId) {
    return __awaiter(this, void 0, void 0, function* () {
        const ONE_MINUTE = 60 * 1000;
        const [turbosCoinB, turbosCoinA] = txb.moveCall({
            target: `${config_1.AggregatorConfig.turbosPackageId}::swap_router::swap_${a2b ? 'a_b' : 'b_a'}_with_return_`,
            arguments: [
                txb.object(poolId),
                coinA,
                amount_in,
                txb.pure.u64(0),
                txb.pure.u128(a2b ? exports.MIN_TICK_INDEX_X64 : exports.MAX_TICK_INDEX_X64),
                txb.pure.bool(byAmountIn),
                txb.pure.address(userAddress),
                txb.pure.u64(Date.now() + ONE_MINUTE * 3),
                txb.object(config_1.AggregatorConfig.clockAddress),
                txb.object(contractVersionId),
            ],
            typeArguments: typeArguments
        });
        return { turbosCoinB, turbosCoinA };
    });
}
