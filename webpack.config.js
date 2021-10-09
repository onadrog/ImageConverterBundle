const webpack = require("webpack");
const { resolve } = require("path");
const dev = process.env.NODE_ENV == "dev";
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const RemoveEmptyScriptsPlugin = require("webpack-remove-empty-scripts");
let mode = dev ? "development" : "production";
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const CompressionPlugin = require("compression-webpack-plugin");

let config = {
  mode,
  module: {
    rules: [
      {
        test: /\.ts?$/,
        use: "ts-loader",
        exclude: /node_modules/,
      },
      {
        test: /\.s?css$/i,
        use: [
          { loader: MiniCssExtractPlugin.loader },
          { loader: "css-loader", options: { sourceMap: true } },
          { loader: "postcss-loader", options: { sourceMap: true } },
          { loader: "sass-loader", options: { sourceMap: true } },
        ],
      },
    ],
  },
  resolve: {
    extensions: [".ts", ".js"],
  },
  entry: {
    image_converter: "./assets/js/image_converter.ts",
    modal: ["./assets/js/modal.ts", "./assets/css/modal.scss"],
  },
  output: {
    path: resolve(__dirname, "public"),
    filename: "imc_[name].js",
  },
  plugins: [
    new CleanWebpackPlugin(),
    new MiniCssExtractPlugin({ filename: "imc_[name].css" }),
    new RemoveEmptyScriptsPlugin(),
  ],
};

if (!dev) {
  config.mode = mode;
  config.optimization = {
    minimize: true,
    minimizer: [
      new TerserPlugin({
        terserOptions: {
          output: {
            comments: false,
          },
        },
        extractComments: false,
      }),
      new CssMinimizerPlugin(),
    ],
  };
  config.loader = {
    test: /.s?css$/,
    use: ["postcss-loader", "sass-loader"],
  };
  config.plugins = [
    new CleanWebpackPlugin(),
    new MiniCssExtractPlugin({ filename: "[name].css" }),
    new CompressionPlugin({
      algorithm: "brotliCompress",
    }),
    new RemoveEmptyScriptsPlugin(),
  ];
}

module.exports = config;
