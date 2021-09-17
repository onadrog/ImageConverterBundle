const webpack = require("webpack");
const { resolve } = require("path");
const dev = process.env.NODE_ENV == "dev";

let mode = dev ? "development" : "production";

module.exports = {
  mode,
  module: {
    rules: [
      {
        test: /\.ts?$/,
        use: "ts-loader",
        exclude: /node_modules/,
      },
    ],
  },
  resolve: {
    extensions: [".ts", ".js"],
  },
  entry: "./assets/js/image_converter.ts",
  output: {
    path: resolve(__dirname, 'public'),
    filename: "image_converter.js",
  },
};
