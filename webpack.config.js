const Encore = require('@symfony/webpack-encore');
let webpack = require("webpack");
//const Dotenv = require("dotenv-webpack");
// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')
    .copyFiles({
        from: "./assets/images/",
        to: "images/[path][name].[ext]",
        pattern: /\.(png|jpg|jpeg|svg)$/,
    })
    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .enableSassLoader()
    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .autoProvidejQuery()
    .autoProvideVariables({
        ProgressBar: "progressbar.js",
    })
    .addPlugin(
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery",
        })
    );

// if (!Encore.isProduction()) {
//     const BundleAnalyzerPlugin =
//         require("webpack-bundle-analyzer").BundleAnalyzerPlugin;
//     Encore.addPlugin(new BundleAnalyzerPlugin());
// }

const config = Encore.getWebpackConfig();
// config.plugins.push(
//     new Dotenv({
//         path: `./.env.${env === "prod" ? "prod" : "dev"}.local`,
//         // path: process.env.NODE_ENV,
//         systemvars: true,
//     })
// );

module.exports = Encore.getWebpackConfig();