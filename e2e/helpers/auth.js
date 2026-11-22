export const USER = {
  name: 'Sukma Ananda',
  email: 'learner@pybeginner.test',
  password: 'password',
};

export async function login(page, user = USER) {
  await page.goto('/login');
  await page.getByLabel('Alamat Email', { exact: true }).fill(user.email);
  await page.getByLabel('Kata Sandi', { exact: true }).fill(user.password);
  await page.getByRole('button', { name: 'Masuk ke PyBeginner' }).click();
}
