// @ts-check
import { test, expect } from '@playwright/test';

async function setEditorCode(page, code) {
  const editor = page.locator('.monaco-editor').first();
  await expect(editor).toBeVisible({ timeout: 15000 });
  await editor.click();
  await page.keyboard.press('Control+A');
  await page.keyboard.insertText(code);
}

async function openSalamExercise(page) {
  await page.goto('/latihan/cetak-salam');
  await expect(page.getByRole('heading', { name: 'Cetak Salam', exact: true })).toBeVisible();
}

test('TC-CODE-001: editor menjalankan kode Python dan menampilkan output', async ({ page }) => {
  await openSalamExercise(page);
  await setEditorCode(page, "print('Halo PyBeginner')");

  await page.getByRole('button', { name: 'Jalankan Kode', exact: true }).click();
  await expect(page.locator('#hasil')).toContainText('Halo PyBeginner', { timeout: 30000 });
});

test('TC-CODE-002: editor menampilkan error untuk nama fungsi yang tidak dikenal', async ({ page }) => {
  await openSalamExercise(page);
  await setEditorCode(page, "prnt('Test Error')");

  await page.getByRole('button', { name: 'Jalankan Kode', exact: true }).click();
  await expect(page.locator('#hasil')).toContainText(/NameError|SyntaxError|not defined/i, { timeout: 30000 });
});

test('TC-CODE-003: runner menghentikan loop tanpa akhir karena batas waktu', async ({ page }) => {
  test.setTimeout(90000);
  await openSalamExercise(page);
  await setEditorCode(page, 'while True: pass');

  await page.getByRole('button', { name: 'Jalankan Kode', exact: true }).click();
  await expect(page.locator('#hasil')).toContainText(/Time Limit Exceeded|timeout|waktu|batas waktu/i, { timeout: 60000 });
});
