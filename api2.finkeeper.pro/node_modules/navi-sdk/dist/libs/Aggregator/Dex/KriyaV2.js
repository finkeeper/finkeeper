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
exports.makeKriyaV2PTB = makeKriyaV2PTB;
const config_1 = require("../config");
function makeKriyaV2PTB(txb, poolId, byAmountIn, coinA, amount, a2b, typeArguments) {
    return __awaiter(this, void 0, void 0, function* () {
        const func = a2b ? 'swap_token_x' : 'swap_token_y';
        const args = [
            txb.object(poolId),
            coinA,
            typeof amount === 'number' ? txb.pure.u64(amount) : amount,
            txb.pure.u64(0),
        ];
        const [coinB] = txb.moveCall({
            target: `${config_1.AggregatorConfig.kriyaV2PackageId}::spot_dex::${func}`,
            typeArguments: typeArguments,
            arguments: args,
        });
        return coinB;
    });
}
