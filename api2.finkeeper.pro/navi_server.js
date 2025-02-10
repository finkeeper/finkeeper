import express from "express"; // ✅ Используем `import`
import { NAVISDKClient } from "navi-sdk";
import { getPoolInfo } from "navi-sdk"; // ✅ Импортируем SDK
import { Sui, USDT, WETH, vSui, haSui, CETUS, NAVX, WBTC, AUSD, wUSDC, nUSDC, ETH, USDY, NS, LorenzoBTC, DEEP, FDUSD, BLUE, BUCK, suiUSDT } from "navi-sdk";

// ✅ Используем переменные окружения (или дефолтные значения)
const mainMnemonic = process.env.MNEMONIC || "ginger argue dose helmet film festival fix target wreck frost armor time";
const rpc = process.env.realRPC || "https://fullnode.mainnet.sui.io:443";
const apiKey = process.env.apiKey;

const client = new NAVISDKClient({ mnemonic: mainMnemonic, networkType: rpc, numberOfAccounts: 1 });


const tokenMap = {
    "SUI": Sui, "USDT": USDT, "WETH": WETH, "vSui": vSui, "haSui": haSui, "CETUS": CETUS, "NAVX": NAVX,
    "WBTC": WBTC, "AUSD": AUSD, "wUSDC": wUSDC, "USDC": nUSDC, "ETH": ETH, "USDY": USDY, "NS": NS,
    "LorenzoBTC": LorenzoBTC, "DEEP": DEEP, "FDUSD": FDUSD, "BLUE": BLUE, "BUCK": BUCK, "suiUSDT": suiUSDT
};


const app = express();
const port = 3001;

app.use(express.json()); // ✅ Позволяет получать JSON-запросы

// 📌 Тестовый API
app.get("/test_json", (req, res) => {
    res.json({ message: "This is a test JSON response from Navi.js !!!!new" });
});

// 📌 API для пулов
app.get("/pool/:token", async (req, res) => {
    const token = req.params.token;
    console.log(`🔍 Запрос данных для токена: ${token}`);

    if (!tokenMap[token]) {
        console.error(`❌ Ошибка: Токен ${token} не найден в tokenMap`);
        return res.status(400).json({ error: "Invalid token name" });
    }

    try {
        const poolData = await getPoolInfo(tokenMap[token]); // ✅ Получаем данные пула
        console.log(`📊 Полученные данные:`, poolData);
        res.json(poolData);
    } catch (error) {
        console.error(`❌ Ошибка в getPoolInfo:`, error);
        res.status(500).json({ error: "Internal Server Error", details: error.message });
    }
});

// ✅ РЕАЛЬНЫЙ ДЕПОЗИТ
app.post("/deposit", async (req, res) => {
    const { mnemonic, token, amount } = req.body;

// ✅ Проверяем токен
    if (!tokenMap[token]) {
        return res.status(400).json({ error: "Invalid token name" });
    }

    try {
        // ✅ Создаём клиента Navi SDK
        const client = new NAVISDKClient({
            mnemonic: mnemonic,
            networkType: "https://fullnode.mainnet.sui.io:443",
            numberOfAccounts: 1
        });

        const account = client.accounts[0]; // ✅ Берем первый аккаунт

        // ✅ Запускаем депозит
        const result = await account.depositToNavi(tokenMap[token], amount);
		
		// ✅ Логируем digest и статус
        console.log(`✅ Депозит завершен!`);
        console.log(`   🔹 Digest: ${result.digest}`);
        console.log(`   🔹 Status: ${result.effects.status.status}`);

        // Отправляем ответ
        res.json({
            success: true,
            message: "Deposit completed",
            digest: result.digest,
            status: result.effects.status.status
        });
		
		
    } catch (error) {
        console.error("❌ Ошибка депозита:", error);
        res.status(500).json({ error: "Deposit failed", details: error.message });
    }
});


app.post("/withdraw", async (req, res) => {
    const { mnemonic, token, amount } = req.body;

    console.log(`🔹 Получен запрос на вывод:`);
    console.log(`   🔹 Mnemonic: [ОК]`);
    console.log(`   🔹 Token: ${token}`);
    console.log(`   🔹 Amount: ${amount}`);

    // ✅ Проверяем, существует ли токен в `tokenMap`
    if (!tokenMap[token]) {
        console.error(`❌ Ошибка: Токен ${token} не найден в tokenMap`);
        return res.status(400).json({ error: "Invalid token name" });
    }

    try {
        // ✅ Создаём клиент Navi SDK (как в `deposit`)
        const client = new NAVISDKClient({
            mnemonic: mnemonic,
            networkType: rpc,
            numberOfAccounts: 1
        });

        const account = client.accounts[0]; // ✅ Берем первый аккаунт

        // ✅ Запускаем `withdraw`
        const result = await account.withdraw(tokenMap[token], amount);

        // ✅ Логируем `digest` и `status`
        console.log(`✅ Вывод завершен!`);
        console.log(`   🔹 Digest: ${result.digest}`);
        console.log(`   🔹 Status: ${result.effects.status.status}`);

        // ✅ Отправляем ответ
        res.json({
            success: true,
            message: "Withdrawal completed",
            digest: result.digest,
            status: result.effects.status.status
        });

    } catch (error) {
        console.error("❌ Ошибка вывода:", error);
        res.status(500).json({ error: "Withdrawal failed", details: error.message });
    }
});





app.listen(port, () => {
    console.log(`🚀 Navi.js API запущен на порту ${port}`);
});
