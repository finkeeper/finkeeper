"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.apiInstance = exports.BridgeConfig = void 0;
exports.config = config;
const axios_1 = __importDefault(require("axios"));
exports.BridgeConfig = {
    baseUrl: "https://open-aggregator-api.naviprotocol.io/find_routes",
    apiKey: "",
};
exports.apiInstance = axios_1.default.create({
    baseURL: exports.BridgeConfig.baseUrl,
    timeout: 30000,
});
function config(newConfig) {
    Object.assign(exports.BridgeConfig, newConfig);
    exports.apiInstance.defaults.baseURL = exports.BridgeConfig.baseUrl;
    if (exports.BridgeConfig.apiKey) {
        exports.apiInstance.defaults.headers.common["x-navi-token"] = exports.BridgeConfig.apiKey;
    }
    else {
        delete exports.apiInstance.defaults.headers.common["x-navi-token"];
    }
}
