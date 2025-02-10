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
exports.makeVSUIPTB = makeVSUIPTB;
const config_1 = require("../config");
const address_1 = require("../../../address");
function makeVSUIPTB(txb, pathTempCoin, a2b) {
    return __awaiter(this, void 0, void 0, function* () {
        let coinB;
        if (a2b) {
            [coinB] = txb.moveCall({
                target: `${config_1.AggregatorConfig.vSuiPackageId}::native_pool::stake_non_entry`,
                typeArguments: [],
                arguments: [
                    txb.object(address_1.vSuiConfig.pool),
                    txb.object(address_1.vSuiConfig.metadata),
                    txb.object(address_1.vSuiConfig.wrapper),
                    pathTempCoin,
                ],
            });
        }
        else {
            const [unstakeTicket] = txb.moveCall({
                target: `${config_1.AggregatorConfig.vSuiPackageId}::native_pool::mint_ticket_non_entry`,
                typeArguments: [],
                arguments: [
                    txb.object(address_1.vSuiConfig.pool),
                    txb.object(address_1.vSuiConfig.metadata),
                    pathTempCoin,
                ],
            });
            [coinB] = txb.moveCall({
                target: `${config_1.AggregatorConfig.vSuiPackageId}::native_pool::burn_ticket_non_entry`,
                arguments: [
                    txb.object(address_1.vSuiConfig.pool),
                    txb.object(address_1.vSuiConfig.wrapper),
                    unstakeTicket,
                ],
                typeArguments: [],
            });
        }
        return coinB;
    });
}
