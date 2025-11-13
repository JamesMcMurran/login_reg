const { test, expect } = require('@playwright/test');

test.describe('Authentication', () => {
  test('should allow a user to register', async ({ page }) => {
    await page.goto('/register');

    const username = `testuser_${Date.now()}`;
    await page.fill('input[name="username"]', username);
    await page.fill('input[name="password"]', 'password');
    await page.fill('input[name="password_again"]', 'password');
    await page.fill('input[name="name"]', 'Test User');
    await page.click('input[type="submit"]');

    await expect(page).toHaveURL('/');
    await expect(page.locator('body')).toContainText('You have been registered and can now log in!');
  });

  test('should allow a user to log in and out', async ({ page }) => {
    // Register a new user
    await page.goto('/register');
    const username = `testuser_${Date.now()}`;
    await page.fill('input[name="username"]', username);
    await page.fill('input[name="password"]', 'password');
    await page.fill('input[name="password_again"]', 'password');
    await page.fill('input[name="name"]', 'Test User');
    await page.click('input[type="submit"]');
    await page.waitForURL('/');

    // Log in
    await page.goto('/login');
    await page.fill('input[name="username"]', username);
    await page.fill('input[name="password"]', 'password');
    await page.click('input[type="submit"]');
    await expect(page).toHaveURL('/');
    await expect(page.locator(`a[href="/profile/${username}"]`)).toHaveText(username);

    // Log out
    await page.click('a[href="/logout"]');
    await expect(page).toHaveURL('/');
    await expect(page.locator('body')).toContainText('You need to login or register.');
  });
});
