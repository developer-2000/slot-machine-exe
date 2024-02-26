const mix = require("laravel-mix");
require("laravel-mix-merge-manifest");
mix.setPublicPath("../../../public");
mix.setResourceRoot("/map");
mix
  .js("app.js", "/map/js")
  .vue({ version: 2 })
  .version()
  .disableNotifications();
// .sass("resources/map/sass/app.scss", "public/map/css");

// mix.webpackConfig({
//   output: {
//     chunkFilename: "js/chunks/[name].js"
//   }
// });

mix.sourceMaps().mergeManifest();
