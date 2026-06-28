# Dependency Analysis: Filament & Livewire Coupling

This document lists all codebase components that depend on Filament or Livewire, which must be refactored or deleted.

---

## 1. Filament Coupling Map

### 1.1 Model Implements & Hooks
The only model with direct coupling is `User.php`:
*   **File:** [User.php](file:///d:/xampp/htdocs/eighteen-custom-apparel/app/Models/User.php)
    *   *Coupling:* Implements `Filament\Models\Contracts\FilamentUser`
    *   *Coupling:* Implements `canAccessPanel(Panel $panel): bool` method.
    *   *Refactor Action:* Remove implementation, imports, and `canAccessPanel` method. RBAC will be managed via a custom Middleware or Policies.

### 1.2 Config & Providers
*   **File:** [AdminPanelProvider.php](file:///d:/xampp/htdocs/eighteen-custom-apparel/app/Providers/Filament/AdminPanelProvider.php)
    *   *Coupling:* Extends `Filament\PanelProvider`
    *   *Refactor Action:* Delete this provider entirely. Remove its registration from `bootstrap/providers.php` or `config/app.php` (if any, Laravel 12 uses auto-discovery or `bootstrap/providers.php`).
*   **File:** [composer.json](file:///d:/xampp/htdocs/eighteen-custom-apparel/composer.json)
    *   *Coupling:* `"filament/filament": "4.0"` in `require` block, and `@php artisan filament:upgrade` command in `post-autoload-dump` scripts.
    *   *Refactor Action:* Remove from requirements and scripts block.

### 1.3 Filament Resources (To Be Deleted/Migrated)
All classes below extend `Filament\Resources\Resource`, `Filament\Resources\Pages\*`, `Filament\Tables\Table`, or `Filament\Schemas\Schema`.
These directories must be migrated to standard Laravel Controller + Blade + Tailwind + AlpineJS structure, then deleted:

1.  **Attendances:**
    *   `app/Filament/Resources/Attendances/AttendanceResource.php`
    *   `app/Filament/Resources/Attendances/Pages/CreateAttendance.php`
    *   `app/Filament/Resources/Attendances/Pages/EditAttendance.php`
    *   `app/Filament/Resources/Attendances/Pages/ListAttendances.php`
    *   `app/Filament/Resources/Attendances/Schemas/AttendanceForm.php`
    *   `app/Filament/Resources/Attendances/Tables/AttendancesTable.php`
2.  **Customers:**
    *   `app/Filament/Resources/Customers/CustomerResource.php`
    *   `app/Filament/Resources/Customers/Pages/CreateCustomer.php`
    *   `app/Filament/Resources/Customers/Pages/EditCustomer.php`
    *   `app/Filament/Resources/Customers/Pages/ListCustomers.php`
    *   `app/Filament/Resources/Customers/Schemas/CustomerForm.php`
    *   `app/Filament/Resources/Customers/Tables/CustomersTable.php`
3.  **Employees:**
    *   `app/Filament/Resources/Employees/EmployeeResource.php`
    *   `app/Filament/Resources/Employees/Pages/CreateEmployee.php`
    *   `app/Filament/Resources/Employees/Pages/EditEmployee.php`
    *   `app/Filament/Resources/Employees/Pages/ListEmployees.php`
    *   `app/Filament/Resources/Employees/Schemas/EmployeeForm.php`
    *   `app/Filament/Resources/Employees/Tables/EmployeesTable.php`
4.  **Invoices:**
    *   `app/Filament/Resources/Invoices/InvoiceResource.php`
    *   `app/Filament/Resources/Invoices/Pages/CreateInvoice.php`
    *   `app/Filament/Resources/Invoices/Pages/ListInvoices.php`
    *   `app/Filament/Resources/Invoices/Schemas/InvoiceForm.php`
    *   `app/Filament/Resources/Invoices/Tables/InvoicesTable.php`
5.  **Orders:**
    *   `app/Filament/Resources/Orders/OrderResource.php`
    *   `app/Filament/Resources/Orders/Pages/CreateOrder.php`
    *   `app/Filament/Resources/Orders/Pages/EditOrder.php`
    *   `app/Filament/Resources/Orders/Pages/ListOrders.php`
    *   `app/Filament/Resources/Orders/Schemas/OrderForm.php`
    *   `app/Filament/Resources/Orders/Tables/OrdersTable.php`
6.  **Payments:**
    *   `app/Filament/Resources/Payments/PaymentResource.php`
    *   `app/Filament/Resources/Payments/Pages/CreatePayment.php`
    *   `app/Filament/Resources/Payments/Pages/EditPayment.php`
    *   `app/Filament/Resources/Payments/Pages/ListPayments.php`
    *   `app/Filament/Resources/Payments/Schemas/PaymentForm.php`
    *   `app/Filament/Resources/Payments/Tables/PaymentsTable.php`
7.  **ProductCategories:**
    *   `app/Filament/Resources/ProductCategories/ProductCategoryResource.php`
    *   `app/Filament/Resources/ProductCategories/Pages/CreateProductCategory.php`
    *   `app/Filament/Resources/ProductCategories/Pages/EditProductCategory.php`
    *   `app/Filament/Resources/ProductCategories/Pages/ListProductCategories.php`
    *   `app/Filament/Resources/ProductCategories/Schemas/ProductCategoryForm.php`
    *   `app/Filament/Resources/ProductCategories/Tables/ProductCategoriesTable.php`
8.  **Products:**
    *   `app/Filament/Resources/Products/ProductResource.php`
    *   `app/Filament/Resources/Products/Pages/CreateProduct.php`
    *   `app/Filament/Resources/Products/Pages/EditProduct.php`
    *   `app/Filament/Resources/Products/Pages/ListProducts.php`
    *   `app/Filament/Resources/Products/Schemas/ProductForm.php`
    *   `app/Filament/Resources/Products/Tables/ProductsTable.php`
9.  **Quotations:**
    *   `app/Filament/Resources/Quotations/QuotationResource.php`
    *   `app/Filament/Resources/Quotations/Pages/CreateQuotation.php`
    *   `app/Filament/Resources/Quotations/Pages/EditQuotation.php`
    *   `app/Filament/Resources/Quotations/Pages/ListQuotations.php`
    *   `app/Filament/Resources/Quotations/Schemas/QuotationForm.php`
    *   `app/Filament/Resources/Quotations/Tables/QuotationsTable.php`

---

## 2. Livewire Coupling Map

These components in `app/Livewire/` use `Livewire\Component` and Livewire file upload traits.
They must be refactored into traditional Laravel controllers and standard Blade views with AlpineJS:

*   **File:** `app/Livewire/PortalLogin.php`
    *   *Livewire logic:* State binding (`$email`, `$password`), validation, redirect.
    *   *Action:* Convert to a traditional controller action (e.g. `PortalAuthController@showLoginForm`, `PortalAuthController@login`).
*   **File:** `app/Livewire/CustomerPortal.php`
    *   *Livewire logic:* Tabs selection, dynamic modals for payment upload, file uploads (`WithFileUploads`).
    *   *Action:* Convert to `CustomerPortalController` with endpoints for dashboard, order details, and payment uploads. Utilize AlpineJS for client-side modal toggles and dynamic tab states.
*   **File:** `app/Livewire/EmployeeAttendance.php`
    *   *Livewire logic:* Geo-location coordinates validation, selfie uploading, dynamic success/error message display.
    *   *Action:* Convert to `AttendanceController` (check-in/check-out endpoints). Use Alpine.js + HTML5 Geolocation API in the Blade frontend to retrieve coordinates and submit via standard form POST with a secure file input.
*   **File:** `app/Livewire/OrderTracker.php`
    *   *Livewire logic:* Search form tracking.
    *   *Action:* Convert to a simple route handled by `OrderTrackerController@track` returning a standard search page.
