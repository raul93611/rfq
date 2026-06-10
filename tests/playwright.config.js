// @ts-check
const { defineConfig, devices } = require('@playwright/test');
const path = require('path');

module.exports = defineConfig({
  testDir: './specs',
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
  workers: 1,
  reporter: 'html',
  globalSetup: require.resolve('./global-setup.js'),
  globalTeardown: require.resolve('./global-teardown.js'),
  testMatch: '**/*.spec.js',
  use: {
    baseURL: 'http://localhost/rfq',
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
    // Playwright's bundled chromium has no build for this host OS — set
    // PW_CHANNEL=chrome to run against the system-installed Google Chrome.
    channel: process.env.PW_CHANNEL || undefined,
  },
  projects: [
    {
      name: 'setup',
      testMatch: /specs\/auth\.setup\.js/,
    },
    {
      name: 'chromium',
      use: {
        ...devices['Desktop Chrome'],
        storageState: path.join(__dirname, '.auth/session.json'),
      },
      dependencies: ['setup'],
    },
  ],
});
