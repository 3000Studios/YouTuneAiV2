/**
 * Jest configuration for YouTuneAI Theme
 */
module.exports = {
  testEnvironment: 'jsdom',
  setupFilesAfterEnv: ['<rootDir>/tests/setup.js'],
  testMatch: [
    '<rootDir>/tests/**/*.test.js',
    '<rootDir>/assets/js/**/*.test.js'
  ],
  collectCoverageFrom: [
    'assets/js/src/**/*.js',
    '!assets/js/src/**/*.test.js',
    '!assets/js/dist/**/*.js'
  ],
  coverageReporters: ['text', 'lcov', 'html'],
  moduleNameMapping: {
    '\\.(css|less|scss|sass)$': 'identity-obj-proxy',
  },
  globals: {
    THREE: {},
    youtuneai_ajax: {
      ajax_url: '/wp-admin/admin-ajax.php',
      nonce: 'test-nonce',
      theme_url: '/wp-content/themes/youtuneai-theme'
    }
  }
};