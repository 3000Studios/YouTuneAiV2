const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = (env, argv) => {
  const isProduction = argv.mode === 'production';
  
  return {
    entry: {
      main: './assets/js/src/main.js',
      avatar: './assets/js/src/avatar.js',
      games: './assets/js/src/games.js',
      vr: './assets/js/src/vr.js',
    },
    output: {
      path: path.resolve(__dirname, 'assets/js/dist'),
      filename: '[name].js',
      clean: true,
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env', '@babel/preset-react'],
            },
          },
        },
        {
          test: /\.css$/,
          use: [
            MiniCssExtractPlugin.loader,
            'css-loader',
            'postcss-loader',
          ],
        },
      ],
    },
    plugins: [
      new MiniCssExtractPlugin({
        filename: '../css/dist/[name].css',
      }),
    ],
    optimization: {
      minimize: isProduction,
    },
    devtool: isProduction ? false : 'source-map',
  };
};