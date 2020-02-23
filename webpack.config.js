const path = require('path');
const ClosureCompilerPlugin = require('closure-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

function resolve(dir) {
    return path.join(__dirname, '/resources/js', dir);
}

module.exports = {
    plugins: [new MiniCssExtractPlugin({
        // Options similar to the same options in webpackOptions.output
        // both options are optional
        filename: '[name].css',
        chunkFilename: '[id].css',
    })],
    module: {
        rules: [
            {
                test: /\.css$/i,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            publicPath: (resourcePath, context) => {
                                return path.relative(path.dirname(resourcePath), context) + '/';
                            },
                        },
                    },
                    'css-loader',
                ],
            },
        ],
    },
    optimization: {
        minimizer: [
            new ClosureCompilerPlugin({
                mode: 'STANDARD',
                // mode: 'AGGRESSIVE_BUNDLE',
                platform: 'javascript',
                childCompilations: false,
                // output: {
                //     library: 'someLibName',
                //     libraryTarget: 'umd',
                //     filename: 'someLibName.js',
                //     auxiliaryComment: 'Test Comment'
                // }
            }, {
                // formatting: 'PRETTY_PRINT'
                debug: false,
                renaming: false
            })
        ]
    }
};
