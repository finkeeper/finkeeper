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
exports.makeAftermathPTB = makeAftermathPTB;
const config_1 = require("../config");
function makeAftermathPTB(txb, poolId, coinA, amountOut, a2b, typeArguments) {
    return __awaiter(this, void 0, void 0, function* () {
        const args = [
            txb.object(poolId),
            txb.object(config_1.AggregatorConfig.aftermathPoolRegistry),
            txb.object(config_1.AggregatorConfig.aftermathFeeVault),
            txb.object(config_1.AggregatorConfig.aftermathTreasury),
            txb.object(config_1.AggregatorConfig.aftermathInsuranceFund),
            txb.object(config_1.AggregatorConfig.aftermathReferralVault),
            coinA,
            txb.pure.u64(amountOut),
            txb.pure.u64('800000000000000000'), // 80%， use https://suivision.xyz/txblock/AvASModFbU6Bmu6FNghqBsVqktnhB9QZKQjdYfnuxNvo?tab=Overview as an reference
        ];
        const res = txb.moveCall({
            target: `${config_1.AggregatorConfig.aftermathPackageId}::swap::swap_exact_in`,
            typeArguments: typeArguments,
            arguments: args,
        });
        return res;
    });
}
