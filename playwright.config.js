// @ts-check
import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './e2e',
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: 0,
  workers: 1,
  reporter: [['html', { open: 'never' }]],
  
  use: {
  baseURL: 'http://127.0.0.1:8000',
  screenshot: 'on',
  trace: 'retain-on-failure',
  video: 'off',
},

  projects: [
    {
      name: 'chrome',
      use: { ...devices['Desktop Chrome'], channel: 'chrome' },
    },
  ],

  webServer: {
    command: 'php artisan serve',
    url: 'http://127.0.0.1:8000',
    reuseExistingServer: true,
  },
});