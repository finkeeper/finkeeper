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
exports.getCoinAmount = getCoinAmount;
exports.getCoinDecimal = getCoinDecimal;
/**
 * Retrieves the amount of a specific coin owned by a sender.
 *
 * @param client - The SuiClient instance used to interact with the blockchain.
 * @param sender - The address of the sender.
 * @param coinType - The type of the coin to retrieve the amount for.
 * @returns A Promise that resolves to the amount of the specified coin owned by the sender.
 * @throws An error if the sender or client is undefined.
 */
function getCoinAmount(client, sender, coinType) {
    return __awaiter(this, void 0, void 0, function* () {
        if (!sender) {
            throw new Error('Sender is undefined.');
        }
        if (!client) {
            throw new Error('Client is undefined.');
        }
        const coinInfo = yield client.getBalance({
            owner: sender,
            coinType
        });
        const tokenBalance = Number(coinInfo.totalBalance);
        console.log("Token Type : ", coinType, "Balance: ", tokenBalance);
        return tokenBalance;
    });
}
/**
 * Retrieves the decimal value for a specific coin type.
 * @param client - The SuiClient instance.
 * @param coinType - The type of coin.
 * @returns A Promise that resolves to the decimal value of the coin.
 */
function getCoinDecimal(client, coinType) {
    return __awaiter(this, void 0, void 0, function* () {
        const coinMetadata = yield client.getCoinMetadata({ coinType: coinType });
        if (coinMetadata)
            return coinMetadata.decimals;
        return 9;
    });
}
