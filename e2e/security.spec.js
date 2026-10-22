// @ts-check
import { test, expect } from '@playwright/test';

test('TC-SEC-001: runner membatasi akses perintah sistem dan tidak membocorkan direktori server', async ({ page }) => {
  await page.goto('/latihan/cek-genap-ganjil');
  await expect(page.getByRole('heading', { name: 'Cek Bilangan Genap atau Ganjil', exact: true })).toBeVisible();

  const editor = page.locator('.monaco-editor').first();
  await expect(editor).toBeVisible({ timeout: 15000 });
  await editor.click();
  await page.keyboard.press('Control+A');
  await page.keyboard.insertText("import os\nos.system('ls /')");

  await page.getByRole('button', { name: 'Jalankan Kode', exact: true }).click();
  const output = page.locator('#hasil');
  await expect(output).toContainText(/blocked|not allowed|permission denied|not permitted|NameError|ModuleNotFoundError|OSError/i, { timeout: 30000 });
  await expect(output).not.toContainText(/(?:^|\s)(?:bin|boot|dev|etc|home|lib|media|mnt|opt|root|run|srv|sys|tmp|usr|var)(?:\s|$)/im);
});
