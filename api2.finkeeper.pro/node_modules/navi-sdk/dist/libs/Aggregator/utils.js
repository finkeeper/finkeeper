"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.generateRefId = generateRefId;
const crypto_js_1 = __importDefault(require("crypto-js"));
// Reserved ref_id
const RESERVED_IDS_ARRAY = [1873161113, 8190801341];
const RESERVED_REF_IDS = new Set(RESERVED_IDS_ARRAY);
// Keep 10 decimal digits
const REF_ID_MOD = Math.pow(10, 10);
/**
 * Generates a unique reference ID based on the provided API key.
 * The reference ID is derived from the SHA-256 hash of the API key,
 * ensuring it is a 10-digit decimal number and does not conflict with reserved IDs.
 *
 * @param {string} apiKey - The API key used to generate the reference ID.
 * @returns {number} A unique reference ID.
 */
function generateRefId(apiKey) {
    // Use SHA-256 to hash the apiKey with crypto-js
    const digest = crypto_js_1.default.SHA256(apiKey).toString(crypto_js_1.default.enc.Hex);
    // Extract the first 16 hexadecimal characters (corresponding to 8 bytes) and convert them to an integer
    let refIdCandidate = parseInt(digest.slice(0, 16), 16);
    // Limit to 10 decimal digits
    refIdCandidate = refIdCandidate % REF_ID_MOD;
    // Avoid conflicts with reserved ref_id
    let offset = 0;
    let finalRefId = refIdCandidate;
    // Try increasing offset each time and take modulo to stay within 10 digits
    while (RESERVED_REF_IDS.has(finalRefId)) {
        throw new Error('Ref ID conflict, please try a new apiKey');
    }
    return finalRefId;
}
