const mix = require("laravel-mix");
const path = require("path");
require("laravel-mix-merge-manifest");
Mix.listen("configReady", function(config) {
  const rules = config.module.rules;
  const targetRegex = /(\.(png|jpe?g|gif)$|^((?!font).)*\.svg$)/;
  for (let rule of rules) {
    if (rule.test.toString() == targetRegex.toString()) {
      rule.exclude = path.resolve(__dirname, "resources/js/icons");
      break;
    }
  }
});
mix.alias({
  "@": "/resources/js",
  "~": "/resources/sass",
});

mix
  .options({
    processCssUrls: true,
    postCss: {
      hideNothingWarning: true,
    },
  })
  .js("resources/js/app.js", "public/js")
  .extract(["vue", "axios", "vuex", "vue-router", "vue-i18n", "element-ui"])
  .vue({ extractStyles: "public/css/vendor.css", version: 2 })
  .sass("resources/js/styles/index.scss", "public/css")
  .version()
  .sourceMaps()
  .mergeManifest();

// .sass("assets/scss/app.scss", "dist", {}, [require("autoprefixer")])
mix
  .webpackConfig({
    stats: {
      children: false,
    },
    resolve: {
      extensions: [".js", ".jsx", ".vue", ".json"],
      alias: {
        "@": path.resolve(__dirname, "resources/js/"),
        "~": path.resolve(__dirname, "resources/js/"),
      },
    },
    output: {
      publicPath: "/",
      filename: "[name].js",
      chunkFilename: "js/[name].[chunkhash].chunk.js",
    },
    module: {
      rules: [
        // {
        //   test: /\.(png|jpe?g|gif)$/i,
        //   use: [
        //     {
        //       loader: "file-loader",
        //       options: {
        //         esModule: false
        //       }
        //     }
        //   ]
        // },
        // { test: /\.vue$/, loader: "vue-loader" },
        // {
        //   test: /\.svg$/,
        //   loader: "svg-sprite-loader",
        //   include: [path.resolve(__dirname, "resources/js")],
        //   options: {
        //     symbolId: "icon-[name]"
        //   }
        // }
      ],
    },
  })
  .disableNotifications();

//     .sass('resources/sass/app.scss', 'public/css')
//     .sourceMaps();
