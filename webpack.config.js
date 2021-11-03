const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
    entry: {
        'angwp.bundle.js': [
            './assets/dev/js/index.js'
        ],
        'angwp_admin.bundle.js': [
            './assets/dev/js/index_admin.js'
        ],
        'angwp_frontend_manager.bundle.js': [
            './assets/dev/js/index_frontend_manager.js'
        ],
        'angwp_parallax.bundle.js': [
            './assets/dev/js/index_parallax.js'
        ]
    },
    output: {
        filename: '[name]',
        path: path.resolve(__dirname, './assets/dist/'),
        //publicPath: ASSET_PATH,
        chunkFilename: '[id].[name].js' // [chunkhash]
    },
    externals: {
        // require("jquery") is external and available
        //  on the global var jQuery
        "jquery": "jQuery"
    },
    module: {
        rules: [{
            test: /\.js$/,
            include: [path.resolve(__dirname, "./assets/dev/js/")],
            exclude: /node_modules/,
            use: {
                loader: 'babel-loader',
                options: {
                    presets: ['@babel/preset-env']
                }
            }
        },
        {
            test: /\.css$/,
            use: [
                MiniCssExtractPlugin.loader,
                "css-loader"
            ]
        },
        {
            test: /\.(png|jpg|gif)$/i,
            use: [
              {
                loader: 'file-loader',
                options: {
                    name: '[name].[ext]',
                    outputPath: './img/'
                }
              }
            ]
        }
    ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].css",
        }),
        /*new webpack.ProvidePlugin({ 
            mjs_cookies: 'js-cookie/src/js.cookie.js',
            //_mdl_CB: path.resolve(__dirname, "./assets/dev/js/_mdl_Close_Buttons.js")
        })*/
    ]
}