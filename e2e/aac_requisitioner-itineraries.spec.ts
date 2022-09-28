import { test, expect } from '@playwright/test';

test('requisitioner can create itinerary', async ({ page }) => {

    // Go to http://localhost:8000/login
    await page.goto('http://localhost:8000/login');

    // Fill input[name="email"]
    await page.locator('input[name="email"]').fill('christineabo@sksu.edu.ph');

    // Fill input[name="password"]
    await page.locator('input[name="password"]').fill('abo123');

    // Click button:has-text("Log in")
    await page.locator('button:has-text("Log in")').click();
    await expect(page).toHaveURL('http://localhost:8000/');

    // Click text=Transactions >> nth=0
    await page.locator('text=Transactions').first().click();
    await expect(page).toHaveURL('http://localhost:8000/requisitioner/transactions');

    // Click a:has-text("Create Itinerary")
    await page.locator('a:has-text("Create Itinerary")').click();
    await expect(page).toHaveURL('http://localhost:8000/requisitioner/itinerary/create');

    // Click text=Select an option
    await page.locator('text=Select an option').click();

    // Click text=1da8bb5d-da29-498b-a74e-9eb3fed5cbc7
    await page.locator('id=choices--travel_order_id-item-choice-1').click();

    // Click text=Move New entry 1 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"] >> nth=0
    await page.locator('text=Move New entry 1 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"]').first().click();

    // Click text=Move New entry 1 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"] >> nth=1
    await page.locator('text=Move New entry 1 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"]').nth(1).click();

    // Click text=Move New entry 1 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"] >> nth=3
    await page.locator('text=Move New entry 1 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"]').nth(3).click();

    // Click button:has-text("Add to itinerary entries") >> nth=0
    await page.locator('button:has-text("Add to itinerary entries")').first().click();

    // Select 1
    await page.locator('text=Mode of Transportation * Select an option Tricycle Bus Taxi Habal-habal Sikad Mu >> select').selectOption('1');

    // Fill text=Move Delete Mode of Transportation * Select an option Tricycle Bus Taxi Habal-ha >> input[type="text"] >> nth=0
    await page.locator('text=Move Delete Mode of Transportation * Select an option Tricycle Bus Taxi Habal-ha >> input[type="text"]').first().fill('Supermarket');

    // Click text=Departure time * Arrival time * >> input[type="text"] >> nth=0
    await page.locator('text=Departure time * Arrival time * >> input[type="text"]').first().click();

    // Press Enter
    await page.locator('[aria-label="Hour"]').first().press('Enter');

    // Click text=Departure time * Arrival time * >> input[type="text"] >> nth=1
    await page.locator('text=Departure time * Arrival time * >> input[type="text"]').nth(1).click();

    // Fill [aria-label="Hour"] >> nth=1
    await page.locator('[aria-label="Hour"]').nth(1).fill('1');

    // Click .p-6 >> nth=0
    await page.locator('.p-6').first().click();

    // Click text=Transportation expenses * Other expenses >> input[type="number"] >> nth=0
    await page.locator('text=Transportation expenses * Other expenses >> input[type="number"]').first().click();

    // Fill text=Transportation expenses * Other expenses >> input[type="number"] >> nth=0
    await page.locator('text=Transportation expenses * Other expenses >> input[type="number"]').first().fill('200');

    // Click text=Transportation expenses * Other expenses >> input[type="number"] >> nth=1
    await page.locator('text=Transportation expenses * Other expenses >> input[type="number"]').nth(1).click();

    // Fill text=Transportation expenses * Other expenses >> input[type="number"] >> nth=1
    await page.locator('text=Transportation expenses * Other expenses >> input[type="number"]').nth(1).fill('300');

    // Click .p-6 >> nth=0
    await page.locator('.p-6').first().click();

    // Click text=Move New entry 2 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"] >> nth=3
    await page.locator('text=Move New entry 2 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"]').nth(3).click();

    // Click text=Move New entry 2 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"] >> nth=1
    await page.locator('text=Move New entry 2 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"]').nth(1).click();

    // Click text=Move New entry 2 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"] >> nth=2
    await page.locator('text=Move New entry 2 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"]').nth(2).click();

    // Click text=Move New entry 3 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"] >> nth=3
    await page.locator('text=Move New entry 3 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"]').nth(3).click();

    // Click [id="itinerary_entries\.fbc39872-a0b7-4519-9e30-5d028ecf7695\.data\.breakfast"]
    await page.locator('text=Move New entry 3 Date * Per diem Coverage Breakfast Lunch Dinner Lodging Itinera >> button[role="switch"]').nth(0).click();

    // Click button:has-text("Save")
    await page.locator('button:has-text("Save")').click();

});
