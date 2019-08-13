var path = require('path');
var HtmlWebpackPlugin = require('html-webpack-plugin');

function findPath() {
	const locations = Array.from(arguments);
	function f() {
		// yo, I heard you like varargs
		const segments = locations.concat(Array.from(arguments));
		return path.resolve.apply(null, segments);
	}

	return f;
}

const src = findPath(__dirname, 'public/src');
const out = findPath(__dirname, 'public/src');
const Directory = './'

module.exports = {
	entry: {
        'script.min': [
            out(Directory, 'vue/index.js')
        ]
    },
    mode: 'development',
    resolve: {
        extensions: ['.js', '.vue']
    },
	output: {
		path: out(Directory, '../dist'),
		filename: '[name].js'
	},
    module: {
        rules: [
            {
                test: /\.vue?$/,
                exclude: /(node_modules)/,
                use: 'vue-loader'
            },
            {
                test: /\.js?$/,
                exclude: /(node_modules)/,
                use: 'babel-loader'
            }
        ]
    },
    plugins: [new HtmlWebpackPlugin({
        template: out(Directory, 'vue/index.html')
    })],
    devServer: {
        historyApiFallback: true
    }
}