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
exports.makeHASUIPTB = makeHASUIPTB;
const config_1 = require("../config");
const address_1 = require("../../../address");
function makeHASUIPTB(txb, pathTempCoin, a2b) {
    return __awaiter(this, void 0, void 0, function* () {
        const func = a2b ? "request_stake_coin" : "request_unstake_instant_coin";
        let coinB;
        if (a2b) {
            [coinB] = txb.moveCall({
                target: `${config_1.AggregatorConfig.haSuiPackageId}::staking::${func}`,
                typeArguments: [],
                arguments: [
                    txb.object(address_1.vSuiConfig.wrapper),
                    txb.object(config_1.AggregatorConfig.haSuiConfigId),
                    pathTempCoin,
                    txb.pure.address("0x0000000000000000000000000000000000000000000000000000000000000000"),
                ],
            });
        }
        else {
            [coinB] = txb.moveCall({
                target: `${config_1.AggregatorConfig.haSuiPackageId}::staking::${func}`,
                typeArguments: [],
                arguments: [
                    txb.object(address_1.vSuiConfig.wrapper),
                    txb.object(config_1.AggregatorConfig.haSuiConfigId),
                    pathTempCoin,
                ],
            });
        }
        return coinB;
    });
}
