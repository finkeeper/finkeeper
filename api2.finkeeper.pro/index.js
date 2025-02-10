import {
  QueryChain,
  OnChainCalls,
} from "@firefly-exchange/library-sui/dist/src/spot/index.js";
import {
  TickMath,
  ClmmPoolUtil,
} from "@firefly-exchange/library-sui/dist/src/spot/clmm/index.js";
import { toBigNumber, toBigNumberStr } from "@firefly-exchange/library-sui";
import { getFullnodeUrl, SuiClient } from "@mysten/sui/client";
import { Ed25519Keypair } from "@mysten/sui/keypairs/ed25519";
import { Transaction } from "@mysten/sui/transactions";
import { mnemonicGenerate } from "@polkadot/util-crypto"; // for mnemonic generation

import { Decimal } from "decimal.js";
import { BN } from "bn.js";

import { mainnet } from "./config.js";

 // import * as secp256k1 from 'secp256k1';
// console.log("✅ `secp256k1` загружен: версия", secp256k1.version || "не найдена");
// console.log("✅ `secp256k1` поддерживает нативные библиотеки:", secp256k1?.native || false);
// 1 acc:
// addr - 0x6e424f5ee02e17651e0c5a5052b85a808d615b3e635f9de7b301880bc607800e

// 2 acc:
// addr - 0x41a17e51a297dbf94b00eae136fdf82196a7eaaffde0b17d21f20c9e507b3569

const rpcUrl = getFullnodeUrl("testnet"); // for testnet

// const client = new SuiClient({ url: rpcUrl });

const client = new SuiClient({ url: "https://fullnode.mainnet.sui.io:443" });

const mnemonic = "jump bronze elephant ticket recipe sand sing left uphold genre glass door"; // FOR TESTING

const keypair = Ed25519Keypair.deriveKeypair(mnemonic);

let qc = new QueryChain(client);
let oc = new OnChainCalls(client, mainnet, { signer: keypair });

async function safeExecute(fn) {
  try {
    const result = await fn();
    process.stdout.write(JSON.stringify(result));
  } catch (error) {
    process.stdout.write(JSON.stringify({ error: error.message }));
  }
}

// get new keypair owned by an hash
// @params
// hash - string
async function createWallet(hash) {
  return await safeExecute(async () => {  // ✅ Открываем скобку {
    
	
	const mnemonic = mnemonicGenerate(12); // mnemonic generate, hash don't used now
	const keypair = Ed25519Keypair.deriveKeypair(mnemonic);
	
	// generate private without mnemo
	// const keypair = new Ed25519Keypair();
    
    // 2️⃣ Получаем SUI-адрес
    const address = keypair.toSuiAddress();
    
    // 3️⃣ Получаем публичный ключ
    const publicKey = keypair.getPublicKey().toBase64();

    // 4️⃣ Получаем приватный ключ (Base64) через getSecretKey()
    const privateKey = keypair.getSecretKey();
        
    return { address, publicKey, privateKey, mnemonic };
  });  // ✅ Закрываем `safeExecute()`
}


// get coins owned by an address
// @params
// address - string

async function balanceOf(address) {
  await safeExecute(async () => client.getCoins({ owner: address }));
}


// transfer coins to an address
// @params
// address - string
// value - int
async function transfer(address, value, mnemonic) {
  await safeExecute(async () => {
	  
	const tx = new Transaction();
    const coins = await client.getCoins({ owner: keypair.toSuiAddress() });

    if (!coins.data.length) {
      throw new Error("No SUI balance available for transfer.");
    }

    const [coin] = tx.splitCoins(tx.gas, [Number(value)]);
    tx.transferObjects([coin], address);

    // ✅ Подписываем и отправляем
    const result = await client.signAndExecuteTransaction({
      signer: keypair,
      transaction: tx,
    });

	// console.log(JSON.stringify({ digest: result.digest }));

    // console.log("🔹 Транзакция отправлена:", result.digest);

    // ✅ Теперь сразу возвращаем `digest`, без проверки статуса
    return { digest: result.digest };

  });
}

// get pool info
// @params
// poolID - string
async function getPool(poolID) {
  await safeExecute(async () => qc.getPool(poolID));
}

// get user positions
// @params
// userAddress - string
async function getUserPoses(userAddress) {
  await safeExecute(async () =>
    qc.getUserPositions(mainnet.BasePackage, userAddress)
  );
}

// swap assets
// @params
// poolID - string
// amount - int
// aToB - bool - If true, then the swap is coinA -> coinB, if false then the swap is coinB -> coinA
// byAmountIn - bool - If true, then you're specifying the amount you're putting in, if false, then you're specifying the amount you're getting back
// slippage - float - The difference between the expected price of a trade and the actual price at which it is executed. This should be a number between 0 and 1, eg: 0.2

async function swap(poolID, amount, aToB, byAmountIn, slippage) {
  await safeExecute(async () => {
    let poolState = await qc.getPool(poolID);

    let iSwapParams = {
      pool: poolState,
      amountIn:
        byAmountIn == true
          ? toBigNumber(
              amount,
              aToB == true
                ? poolState.coin_a.decimals
                : poolState.coin_b.decimals
            )
          : 0,
      amountOut:
        byAmountIn == true
          ? 0
          : toBigNumber(
              amount,
              aToB == true
                ? poolState.coin_b.decimals
                : poolState.coin_a.decimals
            ),
      aToB: aToB,
      byAmountIn: byAmountIn,
      slippage: slippage,
    };

    return oc.swapAssets(iSwapParams);
  });
}

// add liquidity
// @params
// poolID - string
// coinAmount - int
// slippage
// lowerPrice
// upperPrice
async function open(poolID, coinAmount, slippage, lowerPrice, upperPrice) {
  await safeExecute(async () => {
    let pool = await qc.getPool(poolID);

    let coinAmountBN = new BN(toBigNumberStr(coinAmount, pool.coin_a.decimals));
    let lowerTick = TickMath.priceToInitializableTickIndex(
      new Decimal(lowerPrice),
      pool.coin_a.decimals,
      pool.coin_b.decimals,
      pool.ticks_manager.tick_spacing
    );
    let upperTick = TickMath.priceToInitializableTickIndex(
      new Decimal(upperPrice),
      pool.coin_a.decimals,
      pool.coin_b.decimals,
      pool.ticks_manager.tick_spacing
    );

    const curSqrtPrice = new BN(pool.current_sqrt_price);
    const fix_amount_a = true;
    let roundUp = true;

    const liquidityInput = ClmmPoolUtil.estLiquidityAndCoinAmountFromOneAmounts(
      lowerTick,
      upperTick,
      coinAmountBN,
      fix_amount_a,
      roundUp,
      slippage,
      curSqrtPrice
    );

    return oc.openPositionWithFixedAmount(
      pool,
      lowerTick,
      upperTick,
      liquidityInput
    );
  });
}

// collect fees and rewards from position by ID
// @params
// posID - string
async function collect(posID) {
  await safeExecute(async () => {
    let pos = await qc.getPositionDetails(posID);
    let pool = await qc.getPool(pos.pool_id);
    return oc.collectFeeAndRewards(pool, posID);
  });
}
// close position by ID
// all fees and rewards will be collected automatically
// @params
// posID - string
async function close(posID) {
  await safeExecute(async () => {
    let pos = await qc.getPositionDetails(posID);
    let pool = await qc.getPool(pos.pool_id);
    return oc.closePosition(pool, posID);
  });
}



// args parsing
const args = process.argv.slice(2);
const command = args[0];

function parseArg(arg) {
  if (arg === "true") return true;
  if (arg === "false") return false;
  if (!isNaN(arg)) return Number(arg);
  return arg;
}


(async () => {
  if (command === "balance") {
    await balanceOf(args[1]);
  } else if (command === "transfer") {
    await transfer(args[1], parseArg(args[2]));
  } else if (command === "get_pool") {
    await getPool(args[1]);
  } else if (command === "get_user_positions") {
    await getUserPoses(args[1]);
  } else if (command === "swap") {
    await swap(
      args[1],
      parseArg(args[2]),
      parseArg(args[3]),
      parseArg(args[4]),
      parseArg(args[5])
    );
  } else if (command === "open") {
    await open(
      args[1],
      parseArg(args[2]),
      parseArg(args[3]),
      parseArg(args[4]),
      parseArg(args[5])
    );
  } else if (command === "collect") {
    await collect(args[1]);
  } else if (command === "close") {
    await close(args[1]);
  } else if (command === "create_wallet") {  // ✅ Добавляем команду create_wallet
    await createWallet(args[1]);
  } else {
    process.stdout.write(JSON.stringify({ error: "Invalid command" }));
  }
})();