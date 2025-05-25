import js from '@eslint/js'
import tseslint from 'typescript-eslint'
import vue from 'eslint-plugin-vue'
import unicorn from 'eslint-plugin-unicorn'
import prettier from 'eslint-plugin-prettier'
import globals from 'globals'

export default [
  js.configs.recommended,
  tseslint.configs.recommended,
  vue.configs['flat/recommended'],
  {
    files: ['**/*.{ts,vue}'],
    languageOptions: {
      parser: tseslint.parser,
      parserOptions: {
        ecmaVersion: 'latest',
        sourceType: 'module'
      },
      globals: {
        ...globals.browser,
        ...globals.node
      }
    },
    plugins: {
      unicorn,
      prettier
    },
    rules: {
      'prettier/prettier': [
        'error',
        {
          singleQuote: true,
          semi: true,
          trailingComma: 'es5',
          printWidth: 80,
          endOfLine: 'lf'
        }
      ],
      'unicorn/array-element-newline': ['error', { multiline: true, minItems: 1 }]
    }
  }
]
