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

async function openGenapExercise(page) {
  await page.goto('/latihan/cek-genap-ganjil');
  await expect(page.getByRole('heading', { name: 'Cek Bilangan Genap atau Ganjil', exact: true })).toBeVisible();
}

test('TC-CODE-004: solusi benar mendapat status Accepted dan skor 100', async ({ page }) => {
  await login(page);
  await openGenapExercise(page);
  await setEditorCode(page, 'angka = int(input())\nprint("Genap" if angka % 2 == 0 else "Ganjil")');

  const submissionResponse = page.waitForResponse((response) =>
    response.url().includes('/submissions') && response.request().method() === 'POST'
  );
  await page.getByRole('button', { name: 'Kirim & Nilai Jawaban', exact: true }).click();
  const response = await submissionResponse;
  const result = await response.json();

  expect(response.ok()).toBe(true);
  expect(result.status).toBe('accepted');
  expect(result.score).toBe(100);
  await expect(page.locator('#hasil')).toContainText('100');
  await expect(page.locator('#hasil')).toContainText('Mantap! Semua test case lolos.');
});

test('TC-CODE-005: solusi salah mendapat Wrong Answer dengan detail hasil uji', async ({ page }) => {
  await login(page);
  await openGenapExercise(page);
  await setEditorCode(page, 'angka = int(input())\nprint("Salah")');

  const submissionResponse = page.waitForResponse((response) =>
    response.url().includes('/submissions') && response.request().method() === 'POST'
  );
  await page.getByRole('button', { name: 'Kirim & Nilai Jawaban', exact: true }).click();
  const response = await submissionResponse;
  const result = await response.json();

  expect(response.ok()).toBe(true);
  expect(result.status).toBe('failed');
  expect(result.score).toBeLessThan(100);
  await expect(page.locator('#hasil')).toContainText('Belum tepat.');
  await expect(page.locator('#hasil')).toContainText('Diharapkan:');
  await expect(page.locator('#hasil')).toContainText('Keluaran:');
});
