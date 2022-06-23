const mix = require("laravel-mix");

mix.webpackConfig({
  resolve: {
    extensions: [".js", ".vue"],
    alias: {
      "@": path.resolve(__dirname, "resources/js"),
    },
  },
});

mix.options({
  postCss: [require("autoprefixer")],
});

mix
  .js("resources/js/app.js", "public/js")
  .sourceMaps()
  .version()
  .extract(["vue"]);

mix.copyDirectory("resources/js/assets/img", "public/img");
mix.copyDirectory("resources/js/assets/css", "public/css");

if (mix.inProduction()) {
  mix.version();
}

mix.disableNotifications();
