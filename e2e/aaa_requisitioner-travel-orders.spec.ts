import { test, expect } from '@playwright/test';

test('requisitioner can create travel order', async ({ page }) => {

    // Go to http://localhost:8000/login
    await page.goto('http://localhost:8000/login');

    // Fill input[name="email"]
    await page.locator('input[name="email"]').fill('christineabo@sksu.edu.ph');

    // Click input[name="password"]
    await page.locator('input[name="password"]').click();

    // Fill input[name="password"]
    await page.locator('input[name="password"]').fill('abo123');

    // Click button:has-text("Log in")
    await page.locator('button:has-text("Log in")').click();
    await expect(page).toHaveURL('http://localhost:8000/');

    // Click text=Transactions >> nth=0
    await page.locator('text=Transactions').first().click();
    await expect(page).toHaveURL('http://localhost:8000/requisitioner/transactions');

    // Click a:has-text("Create Travel Order")
    await page.locator('a:has-text("Create Travel Order")').click();
    await expect(page).toHaveURL('http://localhost:8000/requisitioner/travel-orders/create');

    // Select 1
    await page.locator('text=Travel order type * Select an option >> select').selectOption('1');

    // Click text=Applicants * CHRISTINE P. ABO, PhDJULIE E. ALBANO, PhDLYNETTE G. PENIEROHELEN M. >> [placeholder="Select an option"]
    await page.locator('text=Applicants * CHRISTINE P. ABO, PhDJULIE E. ALBANO, PhDLYNETTE G. PENIEROHELEN M. >> [placeholder="Select an option"]').click();

    // Click #choices--applicants-item-choice-1
    await page.locator('#choices--applicants-item-choice-1').click();

    // Click .min-h-screen > div > .hidden > div > div
    await page.locator('.min-h-screen > div > .hidden > div > div').click();

    // Click text=Signatories * CHRISTINE P. ABO, PhDJULIE E. ALBANO, PhDLYNETTE G. PENIEROHELEN M >> [placeholder="Select an option"]
    await page.locator('text=Signatories * CHRISTINE P. ABO, PhDJULIE E. ALBANO, PhDLYNETTE G. PENIEROHELEN M >> [placeholder="Select an option"]').click();

    // Click #choices--signatories-item-choice-5
    await page.locator('#choices--signatories-item-choice-5').click();

    // Click .min-h-screen > div > .hidden > div > div
    await page.locator('.min-h-screen > div > .hidden > div > div').click();

    // Fill textarea
    await page.locator('textarea').fill('This is a test travel order.');

    // Click .min-h-screen > div > .hidden > div > div
    await page.locator('.min-h-screen > div > .hidden > div > div').click();

    // Click button[role="switch"]
    await page.locator('button[role="switch"]').click();

    // Fill text=Has registration Registration amount * >> input[type="number"]
    await page.locator('text=Has registration Registration amount * >> input[type="number"]').fill('100');

    // Click input[type="text"] >> nth=0
    await page.locator('input[type="text"]').first().click();

    // Click text=14 >> nth=0
    await page.locator('text=14').first().click();

    // Click input[type="text"] >> nth=1
    await page.locator('input[type="text"]').nth(1).click();

    // Click text=16 >> nth=1
    await page.locator('text=16').nth(1).click();

    // Select 04
    await page.locator('text=Region * Select an option REGION I (ILOCOS REGION) REGION II (CAGAYAN VALLEY) RE >> select').selectOption('04');

    // Select 0421
    await page.locator('text=Province * Select an option BATANGAS CAVITE LAGUNA QUEZON RIZAL >> select').selectOption('0421');

    // Select 042112
    await page.locator('text=City * Select an option ALFONSO AMADEO BACOOR CITY CARMONA CAVITE CITY CITY OF D >> select').selectOption('042112');

    // Click text=Destination Region * Select an option REGION I (ILOCOS REGION) REGION II (CAGAYA >> input[type="text"]
    await page.locator('text=Destination Region * Select an option REGION I (ILOCOS REGION) REGION II (CAGAYA >> input[type="text"]').click();

    // Click button:has-text("Save")
    await page.locator('button:has-text("Save")').click();

    await expect(page).toHaveURL(new RegExp('http://localhost:8000/requisitioner/travel-orders/[0-9]+'));
    await expect(page.locator('text=This is a test travel order.')).toBeVisible();
});
