const path = require("path");
const webpack = require("webpack");

module.exports = {
  entry: "./src/script.js",
  output: {
    filename: "bundle.js",
    path: path.resolve(__dirname, "dist"),
    libraryTarget: "umd", // Добавлено для совместимости модулей
  },
  module: {
    rules: [
      {
        test: /\.m?js$/,
        type: "javascript/auto",
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"],
          },
        },
      },
    ],
  },
  resolve: {
    extensions: [".js", ".mjs"],
    fallback: {
      process: require.resolve("process/browser"),
      buffer: require.resolve("buffer/"),
    },
  },
  plugins: [
    new webpack.ProvidePlugin({
      process: "process/browser",
      Buffer: ["buffer", "Buffer"],
    }),
  ],
  mode: "development",
};
