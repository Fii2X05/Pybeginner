// @ts-check
import { test, expect } from '@playwright/test';
import { login } from './helpers/auth.js';

async function setEditorCode(page, code) {
  const editor = page.locator('.monaco-editor').first();
  await expect(editor).toBeVisible({ timeout: 15000 });
  await editor.click();
  await page.keyboard.press('Control+A');
  await page.keyboard.insertText(code);
}

test('TC-HIST-001: submission baru tampil di riwayat beserta status dan runtime', async ({ page }) => {
  await login(page);
  await page.goto('/latihan/cek-genap-ganjil');
  await expect(page.getByRole('heading', { name: 'Cek Bilangan Genap atau Ganjil', exact: true })).toBeVisible();
  await setEditorCode(page, 'angka = int(input())\nprint("Genap" if angka % 2 == 0 else "Ganjil")');

  const submissionResponse = page.waitForResponse((response) =>
    response.url().includes('/submissions') && response.request().method() === 'POST'
  );
  await page.getByRole('button', { name: 'Kirim & Nilai Jawaban', exact: true }).click();
  const response = await submissionResponse;
  expect(response.ok()).toBe(true);

  await page.goto('/riwayat-submission');
  await expect(page.getByText(/#SUB-\d+/).first()).toBeVisible();
  await expect(page.getByRole('heading', { name: 'Cek Bilangan Genap atau Ganjil', exact: true })).toBeVisible();
  await expect(page.getByText(/Lolos Semua Uji \(100\/100\)|Sebagian Lolos/).first()).toBeVisible();
  await expect(page.getByText(/\d+(\.\d+)?s/).first()).toBeVisible();
  await expect(page.getByText(/ago|from now|yang lalu|dari sekarang/i).first()).toBeVisible();
});

test('TC-HIST-002: pagination riwayat untuk lebih dari 10 submission', async ({ page }) => {
  test.fixme(true, 'Belum ada seeder khusus data riwayat; membuat lebih dari 10 submission memerlukan lebih dari 10 panggilan Judge0 dan dapat membebani layanan uji.');
  await login(page);
  await page.goto('/riwayat-submission');
});
