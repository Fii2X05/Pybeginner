// @ts-check
import { test, expect } from '@playwright/test';
import { login } from './helpers/auth.js';

test('TC-MOD-003: pengguna dapat menandai modul selesai', async ({ page }) => {
  await login(page);
  await page.goto('/modul/01/dasar-python');

  const resetButton = page.getByRole('button', { name: /Modul Selesai \(Klik untuk Ulangi\)/ });
  if (await resetButton.isVisible()) {
    await resetButton.click();
  }

  await page.getByRole('button', { name: 'Tandai Modul Selesai', exact: true }).click();

  await expect(page.getByText('Selamat! Modul 1 berhasil diselesaikan.')).toBeVisible();
  await expect(page.getByRole('button', { name: /Modul Selesai/ })).toBeVisible();
});

test('TC-MOD-004: guest tidak dapat menandai modul selesai', async ({ page }) => {
  await page.goto('/modul/01/dasar-python');

  // Guest navbar tidak menampilkan tombol ini; pastikan route POST tetap meminta autentikasi.
  await expect(page.getByRole('button', { name: /Tandai Modul Selesai/ })).toHaveCount(0);
  const csrfToken = await page.locator('meta[name="csrf-token"]').getAttribute('content');
  const response = await page.request.post('/modul/1/toggle-selesai', {
    maxRedirects: 0,
    headers: {
      Referer: 'http://127.0.0.1:8000/modul/01/dasar-python',
      'X-CSRF-TOKEN': csrfToken ?? '',
    },
  });

  expect(response.status()).toBe(302);
  expect(response.headers().location).toMatch(/\/login/);
});
