// import { TonConnectUI } from "@tonconnect/ui";
import {
  Factory,
  MAINNET_FACTORY_ADDR,
  Asset,
  VaultNative,
  PoolType,
  ReadinessStatus,
  JettonRoot,
  VaultJetton,
} from "@dedust/sdk";
import { DistributionAccount, DistributionPool } from "@dedust/apiary-v1";
import { toNano, beginCell, storeStateInit } from "@ton/core";
import { Address, Sender, SenderArguments, TonClient4 } from "@ton/ton";

document.addEventListener("DOMContentLoaded", () => {
  class TonConnectSender {
    constructor(tonConnect, address) {
      this.tonConnect = tonConnect;
      this.address = address;
    }

    async send(args) {
      await this.tonConnect.sendTransaction({
        validUntil: Date.now() + 1000000,
        messages: [
          {
            address: args.to.toString(),
            amount: args.value.toString(),
            payload: args.body?.toBoc().toString("base64"),
            stateInit: args.init
              ? beginCell()
                  .store(storeStateInit(args.init))
                  .endCell()
                  .toBoc()
                  .toString("base64")
              : undefined,
          },
        ],
      });
    }
  }

  const tonConnectUI = new TON_CONNECT_UI.TonConnectUI({
    manifestUrl: "https://dedmakar.pythonanywhere.com/", // TODO: ЗАМЕНИТЬ НА ДРУГОЙ tonconnect-manifest
    buttonRootId: "ton-connect",
  });

  let sender;

  async function initializeSender() {
    try {
      const connectedWallet = await tonConnectUI.connectWallet();
      const connectedAddress = Address.parse(connectedWallet.account.address);
      sender = new TonConnectSender(tonConnectUI, connectedAddress);
      console.log(
        "Sender initialized with address:",
        connectedAddress.toString()
      );
    } catch (error) {
      console.error("Failed to initialize sender:", error);
      alert("Error initializing sender. Please try again.");
    }
  }

  // Инициализация TonClient и Factory
  const tonClient = new TonClient4({
    endpoint: "https://mainnet-v4.tonhubapi.com",
  });

  const factory = tonClient.open(
    Factory.createFromAddress(MAINNET_FACTORY_ADDR) // DeDust factory address
  );

  const USDT_ADDRESS = Address.parse(
    "EQCxE6mUtQJKFnGfaROTKOt1lZbDiiX1kCixRv7Nw2Id_sDs"
  );

  const AQUAUSD_ADDRESS = Address.parse(
    "EQAWDyxARSl3ol2G1RMLMwepr3v6Ter5ls3jiAlheKshgg0K"
  );

  const TON = Asset.native(); // DECIMALS - 9
  const USDT = Asset.jetton(USDT_ADDRESS); // DECIMALS - 6
  const AQUAUSD = Asset.jetton(AQUAUSD_ADDRESS); // DECIMALS - 9

  function delay(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
  }

  async function performSwapTonJetton(jetton, amount, queryId) {
    try {
      console.log("Swap initiated...");

      const tonVault = tonClient.open(await factory.getNativeVault());

      const amountIn = toNano("10"); // 0.01 TON - amount

      const pool = tonClient.open(
        await factory.getPool(PoolType.VOLATILE, [TON, jetton])
      );

      if ((await pool.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error(`Pool (TON, ${jetton}) does not exist.`);
      }

      if ((await tonVault.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error("Vault (TON) does not exist.");
      }

      const result = await tonVault.sendSwap(sender, {
        queryId: 337, // queryId
        poolAddress: pool.address,
        amount: amountIn,
        gasAmount: toNano("0.1"),
      });

      console.log("Swap result:", result);
      alert("Swap completed successfully!");
    } catch (error) {
      console.error("Error during swap:", error);
      alert("Swap failed. Please try again.");
    }
  }

  async function performSwapJettonTon(jettonAddress, amount, queryId) {
    try {
      console.log("Swap initiated...");

      const JETTON_ADDRESS = Address.parse(jettonAddress);

      const jetton = Asset.jetton(JETTON_ADDRESS);

      const tonVault = tonClient.open(await factory.getNativeVault());

      const currentWallet = Address.parse(tonConnectUI.account.address);

      const amountIn = toNano("0.0001"); // 0.0001 jetton = 0.1 USDT - amount - 9 decimals

      const pool = tonClient.open(
        await factory.getPool(PoolType.VOLATILE, [jetton, TON])
      );

      let poolAddress = pool.address;

      if ((await pool.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error(`Pool (${jetton}, TON) does not exist.`);
      }

      if ((await tonVault.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error("Vault (TON) does not exist.");
      }

      const root = tonClient.open(JettonRoot.createFromAddress(JETTON_ADDRESS));
      const wallet = tonClient.open(await root.getWallet(currentWallet));

      const vault = tonClient.open(
        await factory.getJettonVault(JETTON_ADDRESS)
      );

      const result = await wallet.sendTransfer(sender, toNano("0.3"), {
        queryId: 337, // queryId
        amount: amountIn,
        destination: vault.address,
        responseAddress: currentWallet, // return gas to user
        forwardAmount: toNano("0.25"),
        forwardPayload: VaultJetton.createSwapPayload({ poolAddress }),
      });

      console.log("Swap result:", result);
      alert("Swap completed successfully!");
    } catch (error) {
      console.error("Error during swap:", error);
      alert("Swap failed. Please try again.");
    }
  }

  async function performSwapJettons(
    jettonAddress1,
    jettonAddress2,
    amount,
    queryId
  ) {
    try {
      console.log("Swap initiated...");

      const JETTON_ADDRESS_F = Address.parse(jettonAddress1);
      const JETTON_ADDRESS_S = Address.parse(jettonAddress2);

      const jetton_f = Asset.jetton(JETTON_ADDRESS_F);
      const jetton_s = Asset.jetton(JETTON_ADDRESS_S);

      const currentWallet = Address.parse(tonConnectUI.account.address);

      const amountIn = toNano(amount); // 0.0001 jetton = 0.1 USDT - amount - 9 decimals

      let pool = tonClient.open(
        await factory.getPool(PoolType.VOLATILE, [jetton_f, jetton_s])
      );

      if ((await pool.getReadinessStatus()) !== ReadinessStatus.READY) {
        console.log("VOLATILE POOL NOT FOUND! TRYING STABLE...");
        pool = tonClient.open(
          await factory.getPool(PoolType.STABLE, [jetton_f, jetton_s]) // firstToken, secondToken
        );
        if ((await pool.getReadinessStatus()) !== ReadinessStatus.READY) {
          pool = tonClient.open(
            await factory.getPool(PoolType.STABLE, [jetton_s, jetton_f]) // secondToken, firstToken
          );
          if ((await pool.getReadinessStatus()) !== ReadinessStatus.READY) {
            throw new Error(`Pool (${jetton_f}, ${jetton_s}) does not exist.`);
          }
        }
      }

      const root = tonClient.open(
        JettonRoot.createFromAddress(JETTON_ADDRESS_F)
      );
      const wallet = tonClient.open(await root.getWallet(currentWallet));

      const vault = tonClient.open(
        await factory.getJettonVault(JETTON_ADDRESS_F)
      );

      const result = await wallet.sendTransfer(sender, toNano("0.3"), {
        queryId: queryId, // queryId
        amount: amountIn,
        destination: vault.address,
        responseAddress: currentWallet, // return gas to user
        forwardAmount: toNano("0.25"),
        forwardPayload: VaultJetton.createSwapPayload({
          poolAddress: pool.address,
        }),
      });

      console.log("Swap result:", result);
      alert("Swap completed successfully!");
    } catch (error) {
      console.error("Error during swap:", error);
      alert("Swap failed. Please try again.");
    }
  }

  async function performSwapTonAqua(amount, queryId) {
    try {
      console.log("Swap initiated...");

      const currentWallet = Address.parse(tonConnectUI.account.address);

      const tonVault = tonClient.open(await factory.getNativeVault());

      const usdtVault = tonClient.open(
        await factory.getJettonVault(USDT_ADDRESS)
      );

      const usdtRoot = tonClient.open(
        JettonRoot.createFromAddress(USDT_ADDRESS)
      );
      const usdtWallet = tonClient.open(
        await usdtRoot.getWallet(currentWallet)
      );

      const amountIn = toNano("0.01"); // 0.01 TON - amount
      const amountIn2 = toNano("0.00005"); // 0.05 USDT - amount

      const pool = tonClient.open(
        await factory.getPool(PoolType.VOLATILE, [TON, USDT])
      );

      const pool2 = tonClient.open(
        await factory.getPool(PoolType.STABLE, [AQUAUSD, USDT])
      );

      if ((await pool.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error(`Pool (TON, USDT) does not exist.`);
      }

      if ((await pool2.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error(`Pool (USDT, AQUAUSD) does not exist.`);
      }

      if ((await tonVault.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error("Vault (TON) does not exist.");
      }

      if ((await usdtVault.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error("Vault (USDT) does not exist.");
      }

      const result = await tonVault.sendSwap(sender, {
        queryId: 337, // queryId
        poolAddress: pool.address,
        amount: amountIn,
        gasAmount: toNano("0.1"),
      });

      await delay(1000);

      const result2 = await usdtWallet.sendTransfer(sender, toNano("0.3"), {
        queryId: 337, // queryId
        amount: amountIn2,
        destination: usdtVault.address,
        responseAddress: currentWallet, // return gas to user
        forwardAmount: toNano("0.25"),
        forwardPayload: VaultJetton.createSwapPayload({
          poolAddress: pool2.address,
        }),
      });

      console.log("Swap result:", result);
      console.log("Swap result2:", result2);
      alert("Swap completed successfully!");
    } catch (error) {
      console.error("Error during swap:", error);
      alert("Swap failed. Please try again.");
    }
  }

  async function addLiquidityTONUSDT(firstAmount, secondAmount, queryId) {
    try {
      console.log("Add liquidity initiated...");

      const currentWallet = Address.parse(tonConnectUI.account.address);

      const tonAmount = toNano("0.01"); // 0.01 TON - firstAmount
      const usdtAmount = toNano("0.00005"); // 0.05 USDT (6 decimals) - secondAmount

      const tonVault = tonClient.open(await factory.getNativeVault());
      const usdtVault = tonClient.open(
        await factory.getJettonVault(USDT_ADDRESS)
      );

      if ((await tonVault.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error("Vault (TON) does not exist.");
      }

      if ((await usdtVault.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error("Vault (USDT) does not exist.");
      }

      const assets = [TON, USDT];
      const targetBalances = [tonAmount, usdtAmount];

      // ADD TON
      const result = await tonVault.sendDepositLiquidity(sender, {
        poolType: PoolType.VOLATILE,
        assets,
        targetBalances,
        amount: tonAmount + toNano("0.2"),
      });
      // ADD TON END

      // ADD USDT
      const usdtRoot = tonClient.open(
        JettonRoot.createFromAddress(USDT_ADDRESS)
      );
      const usdtWallet = tonClient.open(
        await usdtRoot.getWallet(currentWallet)
      );

      const result2 = await usdtWallet.sendTransfer(sender, toNano("0.5"), {
        queryId: 337, // queryId
        amount: usdtAmount,
        destination: usdtVault.address,
        responseAddress: currentWallet,
        forwardAmount: toNano("0.4"),
        forwardPayload: VaultJetton.createDepositLiquidityPayload({
          poolType: PoolType.VOLATILE,
          assets,
          targetBalances,
        }),
      });
      // ADD USDT END

      console.log("Add TON result:", result);
      console.log("Add USDT result:", result2);
      alert("Add liquidity completed successfully!");
    } catch (error) {
      console.error("Error during swap:", error);
      alert("Add liquidity failed. Please try again.");
    }
  }

  async function addLiquidityAQUAUSDT(firstAmount, secondAmount, queryId) {
    try {
      console.log("Add liquidity initiated...");

      const currentWallet = Address.parse(tonConnectUI.account.address);

      const aquaAmount = toNano("0.00005"); // 0.05 AquaUSD - firstAmount
      const usdtAmount = toNano("0.00005"); // 0.05 USDT - secondAmount

      const aquaVault = tonClient.open(
        await factory.getJettonVault(AQUAUSD_ADDRESS)
      );

      const usdtVault = tonClient.open(
        await factory.getJettonVault(USDT_ADDRESS)
      );

      if ((await aquaVault.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error("Vault (AquaUSD) does not exist.");
      }

      if ((await usdtVault.getReadinessStatus()) !== ReadinessStatus.READY) {
        throw new Error("Vault (TON) does not exist.");
      }

      const assets = [AQUAUSD, USDT];
      const targetBalances = [aquaAmount, usdtAmount];

      // ADD AQUA
      const aquaRoot = tonClient.open(
        JettonRoot.createFromAddress(AQUAUSD_ADDRESS)
      );
      const aquaWallet = tonClient.open(
        await aquaRoot.getWallet(currentWallet)
      );

      const result = await aquaWallet.sendTransfer(sender, toNano("0.55"), {
        queryId: 337, // queryId
        amount: aquaAmount,
        destination: aquaVault.address,
        responseAddress: currentWallet,
        forwardAmount: toNano("0.45"),
        forwardPayload: VaultJetton.createDepositLiquidityPayload({
          poolType: PoolType.STABLE,
          assets,
          targetBalances,
        }),
      });
      // ADD AQUA END

      await delay(5000);

      // ADD USDT
      const usdtRoot = tonClient.open(
        JettonRoot.createFromAddress(USDT_ADDRESS)
      );
      const usdtWallet = tonClient.open(
        await usdtRoot.getWallet(currentWallet)
      );

      const result2 = await usdtWallet.sendTransfer(sender, toNano("0.55"), {
        queryId: 337, // queryId
        amount: usdtAmount,
        destination: usdtVault.address,
        responseAddress: currentWallet,
        forwardAmount: toNano("0.45"),
        forwardPayload: VaultJetton.createDepositLiquidityPayload({
          poolType: PoolType.STABLE,
          assets,
          targetBalances,
        }),
      });
      // ADD USDT END

      console.log("Add AquaUSD result:", result);
      console.log("Add USDT result:", result2);
      alert("Add liquidity completed successfully!");
    } catch (error) {
      console.error("Error during swap:", error);
      alert("Add liquidity failed. Please try again.");
    }
  }

  async function removeLiquidity(firstToken, secondToken, queryId) {
    try {
      console.log("Withdraw liquidity initiated...");

      const currentWallet = Address.parse(tonConnectUI.account.address);

      let pool = tonClient.open(
        await factory.getPool(PoolType.VOLATILE, [firstToken, secondToken]) // firstToken, secondToken
      );

      if ((await pool.getReadinessStatus()) !== ReadinessStatus.READY) {
        console.log("VOLATILE POOL NOT FOUND! TRYING STABLE...");
        pool = tonClient.open(
          await factory.getPool(PoolType.STABLE, [firstToken, secondToken]) // firstToken, secondToken
        );
        if ((await pool.getReadinessStatus()) !== ReadinessStatus.READY) {
          throw new Error(
            `Pool (${firstToken}, ${secondToken}) does not exist.`
          );
        }
      }

      const lpWallet = await tonClient.open(
        await pool.getWallet(currentWallet)
      );

      const result = await lpWallet.sendBurn(sender, toNano("0.5"), {
        queryId: queryId, // queryId
        amount: await lpWallet.getBalance(),
      });

      console.log("Liquidity remove result:", result);
      alert("Remove completed successfully!");
    } catch (error) {
      console.error("Error during liquidity remove:", error);
      alert("Remove failed. Please try again.");
    }
  }

  // pool values:
  // "EQBGCoxXu8a_CdJ5r1u2iiWUvJAAHvVU7qZgEmfKRSfBf2CW" - AquaUSD/USDT
  // "EQA-X_yo3fzzbDbJ_0bzFWKqtRuZFIRa1sJsveZJ1YpViO3r" - TON/USDT
  async function claimRewards(pool, queryId) {
    try {
      const currentWallet = Address.parse(tonConnectUI.account.address);

      const distributionPoolAddress = Address.parse(pool);

      const rawDistributionPool = DistributionPool.createFromAddress(
        distributionPoolAddress
      );
      const distributionPool = await tonClient.open(rawDistributionPool);

      const d = await distributionPool.getRewardsData();

      console.log(d);

      // const rewardsDictionary = await fetchDictionaryFromIpfs(dataUri);
      // if (!rewardsDictionary) {
      //   return;
      // }

      // const proof = rewardsDictionary.generateMerkleProof([currentWallet]);

      // const result = await distributionPool.sendClaim(sender, {
      //   queryId: queryId,
      //   userAddress: currentWallet,
      //   proof,
      // });

      console.log("Claim result:", result);
      alert("Claim completed successfully!");
    } catch (error) {
      console.error("Error during Claim:", error);
      alert("Claim failed. Please try again.");
    }
  }

  // Запускаем функции при загрузке страницы
  initializeSender();

  // Связываем кнопку Swap TON-USDT с функцией performSwapTonJetton
  const swapButton = document.getElementById("swap-tonusdt-button");
  swapButton.addEventListener("click", () => performSwapTonJetton(USDT));

  // Связываем кнопку Swap USDT-TON с функцией performSwapJettonTon
  const swapButton3 = document.getElementById("swap-usdtton-button");
  swapButton3.addEventListener("click", () =>
    performSwapJettonTon("EQCxE6mUtQJKFnGfaROTKOt1lZbDiiX1kCixRv7Nw2Id_sDs")
  );

  const swapButton4 = document.getElementById("swap-usdtaqua-button");
  swapButton4.addEventListener("click", () =>
    performSwapJettons(
      "EQAWDyxARSl3ol2G1RMLMwepr3v6Ter5ls3jiAlheKshgg0K", // AQUAUSD_ADDRESS
      "EQCxE6mUtQJKFnGfaROTKOt1lZbDiiX1kCixRv7Nw2Id_sDs", // USDT_ADDRESS
      "0.0001",
      337999
    )
  );

  // Связываем кнопку Swap TON-AquaUSD с функцией performSwapTonAqua
  const swapButton2 = document.getElementById("swap-tonaqua-button");
  swapButton2.addEventListener("click", performSwapTonAqua);

  // Связываем кнопку TON/USDT Add с функцией addLiquidityTonUsdt
  const addButton = document.getElementById("tonusdt-add-liquidity-button");
  addButton.addEventListener("click", addLiquidityTONUSDT);

  // Связываем кнопку Aqua/USDT Add с функцией addLiquidityTonUsdt
  const addButton2 = document.getElementById("aquausd-add-liquidity-button");
  addButton2.addEventListener("click", addLiquidityAQUAUSDT);

  // Связываем кнопку TON/USDT Remove с функцией removeLiquidity
  const delButton = document.getElementById("tonusdt-remove-liquidity-button");
  delButton.addEventListener("click", () => removeLiquidity(TON, USDT));

  // Связываем кнопку Aqua/USDT Remove с функцией removeLiquidity
  const delButton2 = document.getElementById("aquausd-remove-liquidity-button");
  delButton2.addEventListener("click", () => removeLiquidity(USDT, AQUAUSD));

  // Связываем кнопку Aqua/USDT Claim с функцией claimRewards
  // const claimButton = document.getElementById("aquausd-claim-button");
  // claimButton.addEventListener("click", () =>
  //   // claimRewards("EQDKG4B4fvvdpaNsUD9nz3gp8UwgLb4msONZlEXfB07LV86i", 337)
  //   // claimRewards("EQA-X_yo3fzzbDbJ_0bzFWKqtRuZFIRa1sJsveZJ1YpViO3r", 337)
  //   claimRewards("UQDKG4B4fvvdpaNsUD9nz3gp8UwgLb4msONZlEXfB07LV5Nn", 337)
  // );
});
