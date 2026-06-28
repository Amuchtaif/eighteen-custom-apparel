# Refactor Strategy & Roadmap (Strangler Pattern)

This plan outlines the migration roadmap to transition the Eighteen Custom Apparel ERP from Filament & Livewire to a pure Laravel MVC architecture.

---

## 1. Strangler Pattern Approach

To maintain application stability, we will not rewrite the entire system at once. Instead:
1.  **Run in Parallel:** The new MVC-based dashboard and pages will be built alongside Filament. Filament will run at `/admin` while the new MVC admin dashboard runs at `/dashboard` (or similar).
2.  **Module Migration:** Rebuild each module as a separate MVC controller and set of Blade views.
3.  **Traffic Cut-over:** Once a module is fully verified, update the main routes/navigation to point to the new MVC module.
4.  **Decommission:** Once all modules are migrated, delete the Filament resources, providers, and remove `filament/filament` from `composer.json`.

---

## 2. Refactor Phases & Roadmap

### Phase 1: Authentication, Layout, & RBAC (Foundation)
*   **Actions:**
    1.  Create standard Blade master layouts:
        *   `resources/views/layouts/admin.blade.php` (Sidebar, Header, Main content panel).
        *   `resources/views/layouts/portal.blade.php` (Customer-facing layout).
    2.  Write a custom [RoleMiddleware](file:///d:/xampp/htdocs/eighteen-custom-apparel/app/Http/Middleware/RoleMiddleware.php) to restrict access by role name.
    3.  Create Auth controllers for Admin Login (`app/Http/Controllers/Auth/LoginController.php`) and Customer Portal Login.
    4.  Implement Laravel Policies to govern data access (e.g. `OrderPolicy`, `QuotationPolicy`).

### Phase 2: Catalog & CRM Modules (Low Complexity)
*   **Actions:**
    1.  Create `ProductCategoryController`, `ProductController`, and `CustomerController`.
    2.  Rebuild CRUD views using TailwindCSS v4 and standard pagination.
    3.  Transition sidebar links for Catalog and CRM to point to these new controllers.

### Phase 3: Employees & Attendance (Medium Complexity)
*   **Actions:**
    1.  Create `EmployeeController` and rebuild HR listing views.
    2.  Convert `EmployeeAttendance.php` (Livewire) to `AttendanceController`.
    3.  Build a pure Blade view with AlpineJS to fetch GPS latitude/longitude using `navigator.geolocation.getCurrentPosition()` and submit them alongside the selfie photo upload.

### Phase 4: Quotation & Sales (High Complexity)
*   **Actions:**
    1.  Create `QuotationController`.
    2.  Build the Quotation Form. Use Alpine.js to manage the quotation items repeater array, enabling users to add/remove rows and automatically recalculating unit prices, subtotals, discounts, and grand totals reactively.
    3.  Add an action button and controller method for "Convert to Order", opening a modal on the UI using Alpine.js to collect the production deadline input.

### Phase 5: Orders & Production Pipeline (High Complexity)
*   **Actions:**
    1.  Create `OrderController`.
    2.  Rebuild the Order details page using a tabbed structure (Overview, Player List, Design Files, Production Progress, Payments, Activity Logs).
    3.  Create upload actions for Design Files.
    4.  Build a production progress stepper in Blade + AlpineJS. Add transition buttons that execute stage transitions via the existing `ProductionService`.

### Phase 6: Financials & Customer Portal (High Complexity)
*   **Actions:**
    1.  Create `InvoiceController` and `PaymentController`.
    2.  Build standard payment recording actions with proof uploads.
    3.  Convert the custom customer portal from Livewire to standard controller views (`CustomerPortalController`). Build tabs and upload forms using AlpineJS.

### Phase 7: Reporting Dashboards & Decommissioning
*   **Actions:**
    1.  Build reports view (Sales performance, attendance recaps).
    2.  Rebuild Home/Dashboard widgets.
    3.  Decommission Filament:
        *   Delete `app/Filament/` folder.
        *   Delete `app/Providers/Filament/AdminPanelProvider.php`.
        *   Remove `filament/filament` from `composer.json` and run `composer update`.
        *   Verify the system is fully operational.
