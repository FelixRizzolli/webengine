const path = require('path');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const mode = 'production';

/* TypeScript -> JavaScript */
module.exports = {
    devtool: 'eval-source-map',
    entry: {
        homepage: './public/src/ts/pages/homepage.ts',
        login: './public/src/ts/pages/login.ts',
        register: './public/src/ts/pages/register.ts',
        profile: './public/src/ts/pages/profile.ts',
        contact: './public/src/ts/pages/contact.ts',
    },
    module: {
        rules: [
            {
                test: /\.ts$/,
                use: 'ts-loader',
                include: [path.resolve(__dirname, 'public/src/ts')]
            },
            {
                test: /\.(s[ac]|c)ss$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader',
                    'postcss-loader'
                ]
            }
        ],
    },
    resolve: {
        extensions: [ '.tsx', '.ts', '.js' ],
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'public/js'),
    },
};
