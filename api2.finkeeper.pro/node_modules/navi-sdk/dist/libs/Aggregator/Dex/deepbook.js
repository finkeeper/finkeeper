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
exports.makeDeepbookPTB = makeDeepbookPTB;
const config_1 = require("../config");
function makeDeepbookPTB(txb, poolId, coinA, amountLimit, a2b, typeArguments) {
    return __awaiter(this, void 0, void 0, function* () {
        const func = a2b
            ? "swap_exact_base_for_quote_sponsored"
            : "swap_exact_quote_for_base_sponsored";
        const [baseCoinOut, quoteCoinOut] = txb.moveCall({
            target: `${config_1.AggregatorConfig.deepSponsoredPackageId}::sponsored_deep::${func}`,
            arguments: [
                txb.object(config_1.AggregatorConfig.deepSponsoredPoolConfig),
                txb.object(poolId),
                coinA,
                txb.pure.u64(amountLimit),
                txb.object(config_1.AggregatorConfig.clockAddress),
            ],
            typeArguments: typeArguments,
        });
        return { baseCoinOut, quoteCoinOut };
    });
}
