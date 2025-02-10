import { NAVISDKClient } from "navi-sdk";
import { Sui, USDT, WETH, vSui, haSui, CETUS, NAVX, WBTC, AUSD, wUSDC, nUSDC, ETH, USDY, NS, LorenzoBTC, DEEP, FDUSD, BLUE, BUCK, suiUSDT } from "navi-sdk";

// ✅ Используем переменные окружения (или дефолтные значения)
const mainMnemonic = process.env.MNEMONIC || "ginger argue dose helmet film festival fix target wreck frost armor time";
const rpc = process.env.realRPC || "https://fullnode.mainnet.sui.io:443";
const apiKey = process.env.apiKey;

const client = new NAVISDKClient({ mnemonic: mainMnemonic, networkType: rpc, numberOfAccounts: 1 });

const tokenMap = {
    "SUI": Sui, "USDT": USDT, "WETH": WETH, "vSui": vSui, "haSui": haSui, "CETUS": CETUS, "NAVX": NAVX,
    "WBTC": WBTC, "AUSD": AUSD, "wUSDC": wUSDC, "nUSDC": nUSDC, "ETH": ETH, "USDY": USDY, "NS": NS,
    "LorenzoBTC": LorenzoBTC, "DEEP": DEEP, "FDUSD": FDUSD, "BLUE": BLUE, "BUCK": BUCK, "suiUSDT": suiUSDT
};

// ✅ Получаем аргумент (название токена) из CLI
const tokenName = process.argv[2];

async function getPoolInfo() {
    if (!tokenName || !tokenMap[tokenName]) {
        console.error(JSON.stringify({ error: "Invalid token name" }));
        process.exit(1);
    }

    try {
        const result = await client.getPoolInfo(tokenMap[tokenName]);

        // ✅ Очищаем и выводим JSON
        console.log(JSON.stringify({
            token_price: result.tokenPrice,
            base_supply_rate: result.base_supply_rate,
            base_borrow_rate: result.base_borrow_rate,
            boosted_supply_rate: result.boosted_supply_rate,
            boosted_borrow_rate: result.boosted_borrow_rate || "N/A",  // ✅ Защита от undefined
            symbol: result.symbol,
            rewardTokenAddress: result.rewardTokenAddress || [],
            optimal_borrow_utilization: result.optimal_borrow_utilization,
            liquidation_threshold: result.liquidation_threshold
        }));
    } catch (error) {
        console.error(JSON.stringify({ error: "Failed to fetch pool info", details: error.message }));
    }
}

getPoolInfo();

async function testJsonResponse() {
    const testResponse = {
        message: "This is a test response",
        status: "success",
        timestamp: new Date().toISOString()
    };

    console.log(JSON.stringify(testResponse));  // ✅ Выводим ЧИСТЫЙ JSON
}

// ✅ Если `navi.js` запущен с `test_json`, вызываем тестовую функцию
const command = process.argv[2];
if (command === "test_json") {
    // console.log(JSON.stringify({ message: "This is a test JSON response from Navi.js" }));

	testJsonResponse();	
}




/* let x = Sui;

console.log(x);
*/



//get quote
/* client.getQuote(fromCoinAddress, toCoinAddress, amount, apiKey).then(quote => {
    console.log(quote);
}); */


/*
//dry run swap
account.swap(fromCoinAddress, toCoinAddress, amount, minAmountOut, apiKey).then(result => {
    console.log(result);
});
*/



/* // deposit toNavi works!
account.depositToNavi(Sui, 1e9).then(result => {
    console.log(result);
});
*/

