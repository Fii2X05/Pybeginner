// @ts-check
import { test, expect } from '@playwright/test';
import { USER, login } from './helpers/auth.js';

test.describe('Autentikasi', () => {
  test('TC-AUTH-001: registrasi dengan data valid', async ({ page }) => {
    await page.goto('/register');
    await page.getByLabel('Nama Lengkap', { exact: true }).fill('Budi Test');
    await page.getByLabel('Alamat Email', { exact: true }).fill(`budi${Date.now()}@example.com`);
    await page.getByLabel('Kata Sandi', { exact: true }).fill('Password123!');
    await page.getByLabel('Konfirmasi Kata Sandi', { exact: true }).fill('Password123!');
    await page.getByRole('button', { name: 'Buat Akun Sekarang' }).click();
    await expect(page).not.toHaveURL(/\/register/);
  });

  test('TC-AUTH-002: registrasi dengan email yang sudah terdaftar', async ({ page }) => {
    await page.goto('/register');
    await page.getByLabel('Nama Lengkap', { exact: true }).fill(USER.name);
    await page.getByLabel('Alamat Email', { exact: true }).fill(USER.email);
    await page.getByLabel('Kata Sandi', { exact: true }).fill(USER.password);
    await page.getByLabel('Konfirmasi Kata Sandi', { exact: true }).fill(USER.password);
    await page.getByRole('button', { name: 'Buat Akun Sekarang' }).click();
    await expect(page).toHaveURL(/\/register/);
    await expect(page.getByText(/already been taken|sudah digunakan|telah digunakan/i)).toBeVisible();
  });

  test('TC-AUTH-003: konfirmasi password tidak cocok', async ({ page }) => {
    await page.goto('/register');
    await page.getByLabel('Nama Lengkap', { exact: true }).fill('Budi Test');
    await page.getByLabel('Alamat Email', { exact: true }).fill(`budi${Date.now()}@example.com`);
    await page.getByLabel('Kata Sandi', { exact: true }).fill('Password123!');
    await page.getByLabel('Konfirmasi Kata Sandi', { exact: true }).fill('Berbeda123!');
    await page.getByRole('button', { name: 'Buat Akun Sekarang' }).click();
    await expect(page).toHaveURL(/\/register/);
    await expect(page.getByText(/the password field confirmation does not match/i)).toBeVisible();
  });

  test('TC-AUTH-004: login dengan kredensial valid', async ({ page }) => {
    await login(page);
    await expect(page).not.toHaveURL(/\/login/);
  });

  test('TC-AUTH-005: login dengan password salah', async ({ page }) => {
    await login(page, { ...USER, password: 'WrongPassword' });
    await expect(page).toHaveURL(/\/login/);
    await expect(page.getByText(/email atau kata sandi yang Anda masukkan salah/i)).toBeVisible();
  });

  test('TC-AUTH-006: redirect ke Google OAuth', async ({ page }) => {
    await page.goto('/login');
    await page.getByRole('link', { name: 'Masuk dengan Google', exact: true }).click();
    await expect(page).toHaveURL(/\/login/);
    await expect(page.getByText(/fitur google login belum dikonfigurasi/i)).toBeVisible();
  });

  test('TC-AUTH-007: logout', async ({ page }) => {
    await login(page);
    await page.getByRole('button', { name: 'Keluar', exact: true }).click();
    await page.goto('/riwayat-submission');
    await expect(page).toHaveURL(/\/login/);
  });
});
