export default {
    testEnvironment: 'jsdom',
    moduleFileExtensions: ['js', 'ts', 'json', 'vue'],
    transform: {
        // Process .vue files with vue3-jest
        '^.+\\.vue$': '@vue/vue3-jest',
        // Process .ts files with ts-jest
        '^.+\\.ts$': 'ts-jest',
        // Process .js files with babel-jest
        '^.+\\.js$': 'babel-jest',
    },
    testMatch: ['**/?(*.)+(spec|test).[jt]s?(x)'],
    // Optional: mapping for @ aliases (adjust to match your tsconfig)
    moduleNameMapper: {
        '^lodash-es$': 'lodash',
    },
    transformIgnorePatterns: [
        // This regex tells Jest to transform laravel-precognition
        // even though it's in node_modules
        'node_modules/(?!(laravel-precognition)/)',
    ],
};
