module.exports = {
  plugins: ["transform-vue-jsx", "dynamic-import-node"],
  presets: ["@vue/app"],
  env: {
    development: {
      plugins: ["dynamic-import-node"]
    }
  }
};
