import { test, expect } from '@playwright/test';

test('requisitioner can create disbursement voucher', async ({ page }) => {

    // Go to http://localhost:8000/login
    await page.goto('http://localhost:8000/login');

    // Fill input[name="email"]
    await page.locator('input[name="email"]').fill('christineabo@sksu.edu.ph');

    // Press Tab
    await page.locator('input[name="email"]').press('Tab');

    // Fill input[name="password"]
    await page.locator('input[name="password"]').fill('abo123');

    // Press Enter
    await page.locator('input[name="password"]').press('Enter');
    await expect(page).toHaveURL('http://localhost:8000/');

    // Click text=Transactions >> nth=0
    await page.locator('text=Transactions').first().click();
    await expect(page).toHaveURL('http://localhost:8000/requisitioner/transactions');

    // Click text=Disbursements
    await page.locator('text=Disbursements').click();

    // Click text=Cash Advances
    await page.locator('text=Cash Advances').click();

    // Click text=Local Travel >> nth=0
    await page.locator('text=Local Travel').first().click();
    await expect(page).toHaveURL('http://localhost:8000/requisitioner/disbursement-vouchers/1/create');

    // Click #dv-main-information-form div[role="combobox"] >> text=Select an option
    await page.locator('#dv-main-information-form div[role="combobox"] >> text=Select an option').click();

    // Click text=be1b2fca-f6ec-4c4b-919d-79545121ae47
    await page.locator('id=choices--travel_order_id-item-choice-1').click();
    await expect(page).toHaveURL('http://localhost:8000/requisitioner/disbursement-vouchers/1/create');

    // Select 1
    await page.locator('text=Mode of Payment * Select an option MDS Check Commercial Check ADA Others >> select').selectOption('1');

    // Fill [id="disbursement_voucher_particulars\.0\.responsibility_center"]
    await page.locator('[id="disbursement_voucher_particulars\\.0\\.responsibility_center"]').fill('Davao Seminar');

    // Fill [id="disbursement_voucher_particulars\.0\.mfo_pap"]
    await page.locator('[id="disbursement_voucher_particulars\\.0\\.mfo_pap"]').fill('MFO');

    // Click input[type="number"]
    await page.locator('input[type="number"]').click();

    // Click button:has-text("Next")
    await page.locator('button:has-text("Next")').click();

    // Click button:has-text("Next")
    await page.locator('button:has-text("Next")').click();

    // Click button:has-text("Next")
    await page.locator('button:has-text("Next")').click();

    await page.locator('#dv-signatory >> text=Select an option').click();
    await page.locator('id=choices--signatory_id-item-choice-1').click();
    await page.keyboard.down('Escape');

    // Click button:has-text("Next")
    await page.locator('button:has-text("Next")').click();
    await expect(page).toHaveURL('http://localhost:8000/requisitioner/disbursement-vouchers/1/create');

    // Click button:has-text("Save")
    await page.locator('button:has-text("Save")').click();
    await expect(page).toHaveURL('http://localhost:8000/requisitioner/disbursement-vouchers');

});
