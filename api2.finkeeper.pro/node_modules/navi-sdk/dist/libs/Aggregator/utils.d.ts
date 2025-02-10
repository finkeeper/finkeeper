/**
 * Generates a unique reference ID based on the provided API key.
 * The reference ID is derived from the SHA-256 hash of the API key,
 * ensuring it is a 10-digit decimal number and does not conflict with reserved IDs.
 *
 * @param {string} apiKey - The API key used to generate the reference ID.
 * @returns {number} A unique reference ID.
 */
export declare function generateRefId(apiKey: string): number;
