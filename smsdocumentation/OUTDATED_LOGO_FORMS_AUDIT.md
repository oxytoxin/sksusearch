# Outdated Logo / Header Audit — Printable & Preview Forms

> **Date of audit:** 2026-04-29
> **Status:** Identification only. No code changes have been applied. Use this document as a checklist when the cleanup work is scheduled.

## Background

The Philippines government requires all national government agencies (NGAs), GOCCs, and State Universities and Colleges (SUCs) — including SKSU — to incorporate the **Bagong Pilipinas logo** in their official letterheads, websites, certificates, and other documents. This is per the Memorandum from the Office of the Executive Secretary.

The cash advance reminder forms (FMR, FMD, SCO, Endorsement, FD) in this project already use the up-to-date `<x-sksu-header />` Blade component. Other forms in the system still display older or incomplete headers — this document identifies which ones.

## Reference: the "current/correct" header

**Component:** `resources/views/components/sksu-header.blade.php`

Renders:

- `images/bagong-pilipinas-logo.png` (national government branding)
- `images/sksulogo.png` (SKSU university logo)
- "Republic of the Philippines" / "SULTAN KUDARAT STATE UNIVERSITY" / address
- Contact row: website, email, phone

**Usage in any Blade template:**

```blade
<x-sksu-header />
```

---

## ✅ Forms already using the correct header (no action needed)

These all use `<x-sksu-header />`:

- `resources/views/reports/formal-management-reminder.blade.php`
- `resources/views/reports/formal-management-demand.blade.php`
- `resources/views/reports/show-cause-order.blade.php`
- `resources/views/reports/endorsement-for-f-d.blade.php`
- `resources/views/livewire/reports/formal-management-reminder.blade.php`
- `resources/views/livewire/reports/formal-management-demand.blade.php`
- `resources/views/livewire/reports/show-cause-order.blade.php`
- `resources/views/livewire/reports/endorsement-for-f-d.blade.php`

---

## ❌ Very outdated — uses deprecated logo files (highest priority)

These reference `headerlogo1.png` and/or `headerlogo2.png` — older SKSU logo variants. **No Bagong Pilipinas logo present.**

| # | File | Lines | Notes |
|---|------|-------|-------|
| 1 | `resources/views/livewire/requisitioner/travel-orders/travel-orders-show.blade.php` | 10, 17 | Travel order display / printable |

---

## ⚠️ Outdated — has SKSU logo but missing Bagong Pilipinas

These have the current SKSU logo but **do not include the Bagong Pilipinas logo**.

| # | File | Line | Form purpose |
|---|------|------|--------------|
| 2 | `resources/views/livewire/requisitioner/motorpool/vehicle-request-form-show.blade.php` | 6 | Vehicle request printable |
| 3 | `resources/views/livewire/icu/icu-manage-verified-documents.blade.php` | 18 | ICU verified documents view |
| 4 | `resources/views/livewire/motorpool/requests/fuel-requisition-slip.blade.php` | 321 | Fuel requisition slip (Livewire) |
| 5 | `resources/views/livewire/motorpool/requests/request-show.blade.php` | 12 | Driver's trip ticket |
| 6 | `resources/views/components/disbursement_vouchers/disbursement_voucher_view_no_layout.blade.php` | 18 | Disbursement voucher view (no layout) |
| 7 | `resources/views/components/disbursement_vouchers/disbursement_voucher_view.blade.php` | 13 | Disbursement voucher view (with layout) |
| 8 | `resources/views/components/forms/ctc-preview.blade.php` | 9 | Certificate-to-collect preview |
| 9 | `resources/views/components/motorpool/fuel-requisition-slip.blade.php` | 4 | Fuel requisition slip (component) |

---

## Totals

- ✅ 8 forms current
- ❌ 1 form very outdated (deprecated logo files)
- ⚠️ 8 forms outdated (missing Bagong Pilipinas logo)
- **Total outdated: 9 files** across Travel Orders, Motorpool, Disbursement Vouchers, ICU, and form preview components.

---

## Official placement guidance (per Bagong Pilipinas guidelines)

When fixing, follow the published convention used by other agencies (DOH, DepEd, FDA, etc.):

- **Agency logo** (SKSU) — top-LEFT
- **Bagong Pilipinas logo** — top-RIGHT
- Both must be at least 1 inch wide
- Full-color version is preferred
- Maintain clear space around each logo equal to the height of the letter "B" in the Bagong Pilipinas logo

The current `<x-sksu-header />` component places **both logos on the LEFT side together**. When the cleanup work is done, consider whether to:

- **Keep current layout** (both left) — easier, requires no design rework
- **Realign to "agency left, Bagong Pilipinas right"** to match official guidance and SKSU's own website footer — requires updating the `<x-sksu-header />` component itself, which would automatically propagate to all 8 already-current forms

---

## Cleanup options (when work is scheduled)

For each of the 9 outdated files, two ways to fix:

### Option A — Replace custom header with `<x-sksu-header />` (cleanest)

```blade
{{-- Remove the existing custom <div class="flex">...</div> header block --}}

<x-sksu-header />
```

**Pros:** Consistency across all forms. Future branding updates require editing only one component.
**Cons:** May shift layout if the current form was styled around its old header (different width, padding, border-bottom, contact info, etc.). **Visual review of every changed form is required.**

### Option B — Add Bagong Pilipinas image next to existing SKSU logo (smallest diff)

```blade
{{-- Inside the existing flex container, add this line BEFORE the existing sksulogo.png img: --}}
<img src="{{ asset('images/bagong-pilipinas-logo.png') }}" class="w-16 h-16" alt="Bagong Pilipinas">
```

**Pros:** Minimal change, preserves the form's existing layout.
**Cons:** Headers stay inconsistent across forms. Adding a 64px-wide image may push other elements horizontally — verify the container has space.

---

## Important caveats

1. **Visual review is mandatory after any change.** Logo updates affect rendered layout. Always open the printed PDF or print preview before declaring a form done.
2. **This audit is not exhaustive.** Only files that reference one of the 5 known logo PNGs in `public/images/` were checked. Forms with no header at all, or forms that pull a logo from elsewhere (CDN, base64-encoded image, S3 bucket) would not appear in this list.
3. **Don't bundle the cleanup with other changes.** Logo updates have visual blast radius and should be reviewable as a standalone PR / commit so designers / the boss can sign off easily.

---

## Reference URLs

Official guidance documents on Bagong Pilipinas usage:

- [Bagong Pilipinas Logo and Designs — Presidential Communications Office](https://pco.gov.ph/?special_links=bagong-pilipinas-logo-and-designs)
- [Bagong Pilipinas Logo Usage Guidelines (PDF)](https://www.scribd.com/document/706316931/Bagong-Pilipinas-Guidelines)
- [Memorandum from the Executive Secretary on Bagong Pilipinas Logo Use](https://jur.ph/laws/summary/use-of-bagong-pilipinas-logo-in-positive-government-public-communications)
- [DepEd Bagong Pilipinas Brand Guidelines](https://www.deped.gov.ph/bagong-pilipinas-logo/)
- [SKSU Official Website (footer shows current branding layout)](https://sksu.edu.ph/)

---

## Logo files in this project

Located at `public/images/`:

| File | Status | Notes |
|------|--------|-------|
| `bagong-pilipinas-logo.png` | ✅ Current | National government branding |
| `sksulogo.png` | ✅ Current | SKSU university logo |
| `headerlogo1.png` | ❌ Deprecated | Older SKSU logo variant |
| `headerlogo2.png` | ❌ Deprecated | Older SKSU logo variant |
| `searchlogo.png` | 🆗 In use | SEARCH application logo (not a letterhead logo) |
