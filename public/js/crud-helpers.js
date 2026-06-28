/**
 * CRUD Helpers — Global AJAX utility functions for modal-based CRUD.
 * 
 * Dependencies: Alpine.js (for toast notifications), CSRF meta tag in <head>.
 */

const CrudHelpers = {
    /**
     * Get CSRF token from meta tag.
     */
    getToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    },

    /**
     * Generic AJAX fetch wrapper with JSON handling.
     * @param {string} url 
     * @param {object} options - fetch options
     * @returns {Promise<object>} parsed JSON response
     */
    async request(url, options = {}) {
        const defaults = {
            headers: {
                'X-CSRF-TOKEN': this.getToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        };

        // Merge headers (don't set Content-Type for FormData)
        const mergedHeaders = { ...defaults.headers, ...options.headers };
        const mergedOptions = { ...defaults, ...options, headers: mergedHeaders };

        const response = await fetch(url, mergedOptions);

        // Handle non-JSON responses (redirects, HTML errors)
        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            if (response.ok) {
                return { success: true, message: 'Operasi berhasil.' };
            }
            throw { status: response.status, message: 'Terjadi kesalahan server.' };
        }

        const data = await response.json();

        if (!response.ok) {
            throw { status: response.status, data: data, errors: data.errors || {} };
        }

        return data;
    },

    /**
     * Fetch resource data via GET (for edit modal population).
     * @param {string} url 
     * @returns {Promise<object>}
     */
    async fetchData(url) {
        return this.request(url, { method: 'GET' });
    },

    /**
     * Submit a form via AJAX (JSON body).
     * @param {string} url 
     * @param {string} method - POST, PUT, PATCH
     * @param {object} data - form data as plain object
     * @returns {Promise<object>}
     */
    async submitJson(url, method, data) {
        return this.request(url, {
            method: method === 'PUT' || method === 'PATCH' ? 'POST' : method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                ...data,
                _method: method, // Laravel method spoofing
            }),
        });
    },

    /**
     * Submit a form via AJAX (FormData, supports file uploads).
     * @param {string} url 
     * @param {string} method - POST, PUT, PATCH
     * @param {FormData} formData 
     * @returns {Promise<object>}
     */
    async submitFormData(url, method, formData) {
        if (method !== 'POST') {
            formData.append('_method', method);
        }
        return this.request(url, {
            method: 'POST', // Always POST for FormData with method spoofing
            headers: {}, // Let browser set Content-Type for FormData
            body: formData,
        });
    },

    /**
     * Delete a resource via AJAX.
     * @param {string} url 
     * @returns {Promise<object>}
     */
    async deleteResource(url) {
        return this.request(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ _method: 'DELETE' }),
        });
    },

    /**
     * Display per-field validation errors inside a form.
     * @param {string} formId - The form element's ID
     * @param {object} errors - { field: [messages] }
     */
    showValidationErrors(formId, errors) {
        this.clearValidationErrors(formId);

        const form = document.getElementById(formId);
        if (!form) return;

        Object.keys(errors).forEach(field => {
            // Handle nested fields like items.0.product_id → items[0][product_id]
            const normalizedField = field.replace(/\.(\d+)\./g, '[$1][').replace(/\.([^.]+)$/g, '[$1]');
            
            const input = form.querySelector(`[name="${field}"], [name="${normalizedField}"]`);
            if (input) {
                // Add error styling
                input.classList.add('border-red-400', 'focus:ring-red-400');
                input.classList.remove('border-[#E2E8F0]', 'focus:ring-[#1E90FF]');

                // Insert error message after input
                const errorDiv = document.createElement('div');
                errorDiv.className = 'crud-field-error text-xs text-red-600 mt-1 font-medium';
                errorDiv.textContent = errors[field][0]; // Show first error message
                input.parentNode.insertBefore(errorDiv, input.nextSibling);
            }
        });
    },

    /**
     * Clear all validation errors from a form.
     * @param {string} formId 
     */
    clearValidationErrors(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        // Remove error messages
        form.querySelectorAll('.crud-field-error').forEach(el => el.remove());

        // Reset input styling
        form.querySelectorAll('.border-red-400').forEach(el => {
            el.classList.remove('border-red-400', 'focus:ring-red-400');
            el.classList.add('border-[#E2E8F0]', 'focus:ring-[#1E90FF]');
        });
    },

    /**
     * Serialize a form element into a plain object.
     * Handles nested array fields like items[0][product_id].
     * @param {string} formId 
     * @returns {object}
     */
    serializeForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};

        for (const [key, value] of formData.entries()) {
            // Skip CSRF and method spoofing
            if (key === '_token' || key === '_method') continue;

            // Handle array notation: items[0][product_id]
            const arrayMatch = key.match(/^(\w+)\[(\d+)\]\[(\w+)\]$/);
            if (arrayMatch) {
                const [, arrayName, index, fieldName] = arrayMatch;
                if (!data[arrayName]) data[arrayName] = [];
                if (!data[arrayName][index]) data[arrayName][index] = {};
                data[arrayName][index][fieldName] = value;
                continue;
            }

            // Handle checkbox (unchecked = not in FormData)
            const input = form.querySelector(`[name="${key}"]`);
            if (input && input.type === 'checkbox') {
                data[key] = input.checked ? 1 : 0;
                continue;
            }

            data[key] = value;
        }

        return data;
    },

    showToast(message, type = 'success') {
        // Save to sessionStorage to survive page reload
        try {
            const pending = JSON.parse(sessionStorage.getItem('pending_toasts') || '[]');
            pending.push({ message, type, timestamp: Date.now() });
            sessionStorage.setItem('pending_toasts', JSON.stringify(pending));
        } catch (e) {
            console.error('Failed to save toast to sessionStorage', e);
        }

        // Access the root Alpine component on body
        const body = document.body;
        if (body.__x) {
            // Alpine v2
            body.__x.$data.addToast(message, type);
        } else if (body._x_dataStack) {
            // Alpine v3
            const data = Alpine.$data(body);
            if (data && data.addToast) {
                data.addToast(message, type);
            }
        }
    },

    /**
     * Refresh the current page table (full page reload for server-rendered tables).
     * Preserves search params.
     */
    refreshTable() {
        window.location.reload();
    },

    /**
     * Format number as Indonesian Rupiah.
     * @param {number} amount 
     * @returns {string}
     */
    formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    },

    /**
     * Format input value to Indonesian Rupiah dynamically (for typing).
     * @param {string|number} value
     * @returns {string}
     */
    formatInputRupiah(value) {
        if (value === null || value === undefined || value === '') return '';
        const clean = value.toString().replace(/[^0-9]/g, '');
        if (!clean) return '';
        const formatted = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
        }).format(parseInt(clean, 10));
        return `Rp ${formatted}`;
    },

    /**
     * Parse Indonesian Rupiah string back to raw integer.
     * @param {string} value
     * @returns {number}
     */
    parseInputRupiah(value) {
        if (!value) return 0;
        const clean = value.toString().replace(/[^0-9]/g, '');
        return parseInt(clean, 10) || 0;
    },
};

// Make globally available
window.CrudHelpers = CrudHelpers;
