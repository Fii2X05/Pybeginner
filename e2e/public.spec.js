// @ts-check
import { test, expect } from '@playwright/test';

test('halaman utama PyBeginner terbuka', async ({ page }) => {
  await page.goto('/');
  await expect(page).toHaveTitle(/.+/);
});

test.describe('Autentikasi - proteksi route', () => {
  test('TC-AUTH-008: guest diarahkan ke login saat buka riwayat submission', async ({ page }) => {
    await page.goto('/riwayat-submission');
    await expect(page).toHaveURL(/\/login/);
  });
});

test.describe('Landing Page', () => {
  test('TC-LAND-001: elemen utama landing page tampil', async ({ page }) => {
    await page.goto('/');
    await expect(page.locator('nav').first()).toBeVisible();
    await expect(page.locator('h1').first()).toBeVisible();
    await expect(
      page.getByRole('link', { name: /Mulai Belajar/i }).first()
    ).toBeVisible();
    await expect(page.locator('footer')).toBeVisible();
  });

  test('TC-LAND-002: tombol CTA mengarah ke daftar modul', async ({ page }) => {
    await page.goto('/');
    await page.getByRole('link', { name: /Mulai Belajar/i }).first().click();
    await expect(page).toHaveURL(/\/modul/);
  });
});

test.describe('Landing Page - mobile', () => {
  test.use({ viewport: { width: 375, height: 667 } });

  test('TC-LAND-003: tampilan mobile tidak overflow dan navigasi bisa dibuka', async ({ page }) => {
    await page.goto('/');

    const overflow = await page.evaluate(
      () => document.documentElement.scrollWidth > window.innerWidth
    );
    expect(overflow).toBe(false);

    const hamburger = page
      .locator('button[aria-label*="menu" i], button[aria-controls], #menu-toggle, .hamburger')
      .first();

    if (await hamburger.count()) {
      await hamburger.click();
      await expect(
        page.getByRole('link', { name: /modul/i }).first()
      ).toBeVisible();
    }
  });
});

test.describe('Modul Belajar', () => {
  test('TC-MOD-001: daftar modul tampil', async ({ page }) => {
    await page.goto('/modul');

    const modul = [
      /Dasar Python/i,
      /Variabel/i,
      /Logika/i,
      /Perulangan/i,
      /Fungsi/i,
      /Struktur Data/i,
      /Pandas|NumPy/i,
    ];

    for (const nama of modul) {
      await expect(page.getByText(nama).first()).toBeVisible();
    }
  });

  test('TC-MOD-002: halaman detail modul 01 terbuka', async ({ page }) => {
    await page.goto('/modul');

    const linkModul = page.getByRole('link', {
      name: 'Mulai Modul',
    }).first();

    await expect(linkModul).toBeVisible();
    await expect(linkModul).toHaveAttribute(
      'href',
      /\/modul\/01\/dasar-python$/
    );

    await linkModul.click();

    await expect(page).toHaveURL(/\/modul\/01\/dasar-python$/);
    await expect(page.locator('h1').first()).toContainText(/Dasar Python/i);
  });
});
