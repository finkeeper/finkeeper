import { Quote, SwapOptions } from '../../types';
/**
 * Get a swap quote between two coins using the aggregator API.
 *
 * @param fromCoinAddress - The address of the coin to swap from.
 * @param toCoinAddress - The address of the coin to swap to.
 * @param amountIn - The amount of the fromCoin to swap. Can be a number, string, or bigint.
 * @param apiKey - Optional API key for authentication.
 * @param swapOptions - Optional swap options including baseUrl, dexList, byAmountIn, and depth.
 * @returns A promise that resolves to a Router object containing the swap route details.
 * @throws Will throw an error if the API request fails or returns no data.
 */
export declare function getQuote(fromCoinAddress: string, toCoinAddress: string, amountIn: number | string | bigint, apiKey?: string, swapOptions?: SwapOptions): Promise<Quote>;
