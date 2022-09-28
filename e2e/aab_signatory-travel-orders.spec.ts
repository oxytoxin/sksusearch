import { test, expect } from '@playwright/test';

test('signatory can approve travel order', async ({ page }) => {

    // Go to http://localhost:8000/login
    await page.goto('http://localhost:8000/login');

    // Fill input[name="email"]
    await page.locator('input[name="email"]').fill('rodelyndalayap@sksu.edu.ph');

    // Fill input[name="password"]
    await page.locator('input[name="password"]').fill('dalayap123');

    // Press Enter
    await page.locator('input[name="password"]').press('Enter');
    await expect(page).toHaveURL('http://localhost:8000/');

    // Click button:has-text("Travel Orders") >> nth=0
    await page.locator('button:has-text("Travel Orders")').first().click();

    // Click text=Signatory Travel orders >> nth=0
    await page.locator('text=Signatory Travel orders').first().click();
    await expect(page).toHaveURL('http://localhost:8000/signatory/travel-orders');

    // Click text=View
    await page.locator('text=View').click();
    await expect(page).toHaveURL('http://localhost:8000/signatory/travel-orders/view/2');

    // Click button:has-text("Approve Travel Order")
    await page.locator('button:has-text("Approve Travel Order")').click();
    await expect(page).toHaveURL('http://localhost:8000/signatory/travel-orders/view/2');

    // Click text=OK
    await page.locator('text=OK').click();

    // Click button:has-text("Add note")
    await page.locator('button:has-text("Add note")').click();

    // Fill textarea[name="note"]
    await page.locator('textarea[name="note"]').fill('This is a test sidenote');

    // Click button:has-text("Save Note")
    await page.locator('button:has-text("Save Note")').click();

    // Click text=OK
    await page.locator('text=OK').click();

});
