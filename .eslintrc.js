module.exports = {
  root: true,
  env: {
    node: true,
  },
  extends: [
    'plugin:vue/essential', // or 'plugin:vue/recommended' for stricter rules
    'eslint:recommended',
    '@vue/prettier', // if using Prettier
  ],
  parserOptions: {
    ecmaVersion: 2020,
  },
  rules: {
    'vue/no-parsing-error': ['error', { 'x-invalid-end-tag': false }], // Disable the error
    'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
  },
};
