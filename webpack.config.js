const path = require("path");
const fs = require("fs");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const CopyPlugin = require("copy-webpack-plugin");
const ImageMinimizerPlugin = require("image-minimizer-webpack-plugin");
const { WebpackManifestPlugin } = require("webpack-manifest-plugin");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const WebpackBar = require("webpackbar");

// Custom clean function to only clean public/js and public/css
const cleanFolders = () => {
  const foldersToClean = ["js", "css"];
  const publicPath = path.resolve(__dirname, "public");

  foldersToClean.forEach((folder) => {
    const dir = path.join(publicPath, folder);
    if (fs.existsSync(dir)) {
      fs.readdirSync(dir).forEach((file) => {
        fs.rmSync(path.join(dir, file), { recursive: true, force: true });
      });
    }
  });
};

const isDev = process.env.NODE_ENV === "development";

const outputPath = path.resolve(__dirname, "public");

const config = {
  entry: ["./js/scripts.js", "./scss/main.scss"],
  browserSyncProxy: "http://localhost:10003",
};

cleanFolders();

module.exports = {
  context: path.resolve(__dirname, "styles"),
  entry: config.entry,
  output: {
    filename: isDev ? "js/bundle.js" : "js/bundle.[contenthash:8].js",
    path: outputPath,
    clean: false, // disable webpack's clean because we use our own custom cleaning above
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"],
          },
        },
      },
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: "css-loader",
            options: {
              url: false,
              import: false,
              modules: false,
              sourceMap: isDev,
            },
          },
          {
            loader: "postcss-loader",
            options: {
              postcssOptions: {
                plugins: ["autoprefixer"],
              },
              sourceMap: isDev,
            },
          },
          {
            loader: "sass-loader",
            options: {
              sourceMap: true,
            },
          },
        ],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: isDev ? "css/style.css" : "css/style.[contenthash:8].css",
      chunkFilename: "[id].css",
    }),

    new WebpackManifestPlugin({
      fileName: "webpack.manifest.json",
      publicPath: "/public",
      filter: (file) => /\.(js|css)$/.test(file.name),
    }),
    new BrowserSyncPlugin({
      ui: false,
      notify: false,
      proxy: config.browserSyncProxy,
    }),
    ...(!isDev ? [new WebpackBar()] : []),
  ],
  optimization: {
    minimizer: [
      `...`,
      new CssMinimizerPlugin(),
      ...(!isDev
        ? [
            // new ImageMinimizerPlugin({
            //   minimizer: {
            //     implementation: ImageMinimizerPlugin.imageminMinify,
            //     options: {
            //       plugins: ["optipng", "gifsicle", "svgo"],
            //     },
            //   },
            // }),
          ]
        : []),
    ],
  },
  devtool: isDev ? "source-map" : false,
  stats: "errors-warnings",
  performance: {
    hints: false,
  },
};
