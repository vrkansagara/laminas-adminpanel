const path = require('path');
const ClosureCompilerPlugin = require('closure-webpack-plugin');

function resolve(dir) {
    return path.join(__dirname, '/resources/js', dir);
}

module.exports = {
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
