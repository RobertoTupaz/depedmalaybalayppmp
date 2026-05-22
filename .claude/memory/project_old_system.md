---
name: project_old_system
description: Legacy PPMP system architecture, business rules, and data model being rebuilt in Laravel
metadata:
  type: project
---

# Old PPMP System (ppmp_old_system/)

## What is PPMP?
**Project Procurement Management Plan** — a government procurement tool for DepEd's SGOD (School Governance and Operations Division). Offices plan annual supply purchases per month/quarter.

**Why:** Being rebuilt in Laravel 12 / Livewire / Flux UI. Understanding the old system is essential for feature parity.
**How to apply:** Use this as the reference spec when building features; ensure all reports, business rules, and data flows are preserved.

## Database Schema (4 tables)

### `offices`
- `offices_id`, `office_name`, `groups` (e.g. "SGOD"), `allocation` (budget in PHP)
- `prepared`, `reviewed`, `approved`, `designation1/2/3` (signatories for PPMP report)
- 10 offices in SGOD; allocations range PhP 13,500–27,000

### `supplies`
- `sup_id`, `category_id`, `item` (text description), `uom` (unit of measure), `u_price`, `last_update`, `date_lastBought`
- Large catalog (800+ items)

### `category` (11 categories)
- Common Electrical Supplies, Computer Supplies, Office Supplies, Office Devices, Janitorial Supplies, Legal Size Paper, Office Equipment, Medical Supplies, Seminars and Trainings, Tarpaulin With/Without Layout

### `orders`
- `order_id`, `office_id`, `supply_id`, `groups` (MD5 hash of group name), `quantity`, `month_needed` (YYYY-MM), `date_added`
- One unique record per office+supply+month (duplicate check enforced)

## Core Business Rules

1. **Markup Price (MUP)** = `u_price × 1.1` (10% markup applied everywhere)
2. **Wallet/Budget** = `offices.allocation` — total ordered amount must not exceed this
3. **Available supplies** filtered by `ROUND(u_price,2)*1.1 <= balance`
4. **Month Needed** stored as `YYYY-MM`; used to distribute quantities across Jan–Dec + Q1–Q4 columns in PPMP report
5. **Groups** stored as MD5 hash of group name string in `orders.groups`

## User Flow

1. `index.php` → select office → go to `order.php`
2. `order.php` — two-panel: left=cart sidebar, right=supplies table with "Add to Cart" buttons
3. `cart.php` — form: item detail, marked-up price (read-only), quantity, month needed → submit
4. `process_cart.php` — INSERT into orders, redirect to order.php
5. `update_cart.php` — edit form for existing order
6. `process-update-cart.php` — UPDATE orders
7. `delete-order.php` — DELETE order, redirect to order.php

## Reports

### `my-ppmp.php` — Individual Office PPMP
- Header: "REVISED PROJECT PROCUREMENT MANAGEMENT PLAN FOR CY [next year]"
- Table columns: Item & Spec | Unit | Jan | Feb | Mar | Q1 | Apr | May | Jun | Q2 | Jul | Aug | Sep | Q3 | Oct | Nov | Dec | Q4 | Total Qty | Unit Price | MUP | Total Amount
- Footer: Prepared by / Reviewed by / Approved by (from offices table)

### `group-ppmp.php` — Group-level PPMP
- Same table format as my-ppmp.php but aggregates all offices in a group
- Accessed via MD5 hash of group name: `?group=<md5>`

### `order-summary.php` — Order Summary Table
- Rows = distinct supply items ordered
- Columns = each office in the group
- Shows quantities per office, totals per row

## Admin Functions
- `supply-admin.php` — Add new supply (item, category, unit, price)
- `update-supplies.php` — Edit existing supply
- `process-add-supplies.php` / `process-update-supplies.php` — form handlers
- `remove-supply.php` — delete supply

## Navigation Notes
- No authentication in these pages (basic_functions.php has session/role code but unused here)
- `order-supplies-table.php` is included into order.php — shows supplies filtered by remaining wallet balance
- `list-of-supplies.php` / `list-of-supplies-uneditable.php` — included in admin/public supply views

## Organizational Context
- DepEd SGOD division, Philippines
- System created by Jimdandy S. Lucine (Project Development Officer II)
- Key signatories: Rosalio P. Arangco (EPS), Lorenzo O. Capacio EdD (Chief EPS, SGOD)
