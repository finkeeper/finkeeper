import { SuiClient } from "@mysten/sui/client";
/**
 * Retrieves the amount of a specific coin owned by a sender.
 *
 * @param client - The SuiClient instance used to interact with the blockchain.
 * @param sender - The address of the sender.
 * @param coinType - The type of the coin to retrieve the amount for.
 * @returns A Promise that resolves to the amount of the specified coin owned by the sender.
 * @throws An error if the sender or client is undefined.
 */
export declare function getCoinAmount(client: SuiClient, sender: string, coinType: string): Promise<number>;
/**
 * Retrieves the decimal value for a specific coin type.
 * @param client - The SuiClient instance.
 * @param coinType - The type of coin.
 * @returns A Promise that resolves to the decimal value of the coin.
 */
export declare function getCoinDecimal(client: SuiClient, coinType: string): Promise<any>;
