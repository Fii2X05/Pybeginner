// @ts-check
import { test } from '@playwright/test';

test.skip('TC-COMPAT-001: halaman latihan kompatibel dengan Firefox dan WebKit', async ({ page }) => {
  // Firefox dan WebKit belum terpasang atau dikonfigurasi pada lingkungan Playwright ini.
  await page.goto('/latihan/cetak-salam');
});
