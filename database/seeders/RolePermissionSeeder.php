<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 1. CREATE PERMISSIONS
        // ============================================
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view',    'label' => 'View Dashboard',       'module' => 'dashboard', 'group' => 'General'],

            // Categories
            ['name' => 'categories.view',   'label' => 'View Categories',      'module' => 'categories', 'group' => 'Inventory'],
            ['name' => 'categories.manage', 'label' => 'Manage Categories',    'module' => 'categories', 'group' => 'Inventory'],

            // Products
            ['name' => 'products.view',     'label' => 'View Products',        'module' => 'products', 'group' => 'Inventory'],
            ['name' => 'products.manage',   'label' => 'Manage Products',      'module' => 'products', 'group' => 'Inventory'],

            // Customers
            ['name' => 'customers.view',    'label' => 'View Customers',       'module' => 'customers', 'group' => 'People'],
            ['name' => 'customers.manage',  'label' => 'Manage Customers',     'module' => 'customers', 'group' => 'People'],

            // Suppliers
            ['name' => 'suppliers.view',    'label' => 'View Suppliers',       'module' => 'suppliers', 'group' => 'People'],
            ['name' => 'suppliers.manage',  'label' => 'Manage Suppliers',     'module' => 'suppliers', 'group' => 'People'],

            // Purchases
            ['name' => 'purchases.view',    'label' => 'View Purchases',       'module' => 'purchases', 'group' => 'Transactions'],
            ['name' => 'purchases.manage',  'label' => 'Manage Purchases',     'module' => 'purchases', 'group' => 'Transactions'],

            // Sales / POS
            ['name' => 'sales.view',        'label' => 'View Sales History',   'module' => 'sales', 'group' => 'Transactions'],
            ['name' => 'sales.create',      'label' => 'Create New Sale (POS)','module' => 'sales', 'group' => 'Transactions'],
            ['name' => 'sales.delete',      'label' => 'Delete Sales',         'module' => 'sales', 'group' => 'Transactions'],

            // Customer Payments
            ['name' => 'customer-payments.view',   'label' => 'View Payments',   'module' => 'customer-payments', 'group' => 'Transactions'],
            ['name' => 'customer-payments.manage', 'label' => 'Manage Payments', 'module' => 'customer-payments', 'group' => 'Transactions'],

            // Expenses
            ['name' => 'expenses.view',     'label' => 'View Expenses',        'module' => 'expenses', 'group' => 'Finance'],
            ['name' => 'expenses.manage',   'label' => 'Manage Expenses',      'module' => 'expenses', 'group' => 'Finance'],

            // Reports
            ['name' => 'reports.view',      'label' => 'View Reports',         'module' => 'reports', 'group' => 'Reports'],

            // Users
            ['name' => 'users.view',        'label' => 'View Users',           'module' => 'users', 'group' => 'Admin'],
            ['name' => 'users.manage',      'label' => 'Manage Users',         'module' => 'users', 'group' => 'Admin'],

            // Roles
            ['name' => 'roles.view',        'label' => 'View Roles',           'module' => 'roles', 'group' => 'Admin'],
            ['name' => 'roles.manage',      'label' => 'Manage Roles',         'module' => 'roles', 'group' => 'Admin'],

            // Settings
            ['name' => 'settings.view',     'label' => 'View Settings',        'module' => 'settings', 'group' => 'Admin'],
            ['name' => 'settings.manage',   'label' => 'Manage Settings',      'module' => 'settings', 'group' => 'Admin'],

            // Backup
            ['name' => 'backup.view',   'label' => 'View Backups',   'module' => 'backup', 'group' => 'Admin'],
            ['name' => 'backup.manage', 'label' => 'Manage Backups', 'module' => 'backup', 'group' => 'Admin'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['name' => $p['name']], $p);
        }

        // ============================================
        // 2. CREATE ROLES
        // ============================================
        $roles = [
            [
                'name'        => 'admin',
                'label'       => 'Administrator',
                'description' => 'Full access to everything',
            ],
            [
                'name'        => 'manager',
                'label'       => 'Manager',
                'description' => 'Everything except Users, Roles & Settings',
            ],
            [
                'name'        => 'cashier',
                'label'       => 'Cashier',
                'description' => 'Only Dashboard & Sales',
            ],
            [
                'name'        => 'accountant',
                'label'       => 'Accountant',
                'description' => 'Reports & Expenses only',
            ],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(['name' => $r['name']], $r);
        }

        // ============================================
        // 3. ASSIGN PERMISSIONS TO ROLES
        // ============================================

        // ---- ADMIN: everything ----
        $admin = Role::where('name', 'admin')->first();
        $admin->permissions()->sync(Permission::pluck('id'));

        // ---- MANAGER: everything except users.*, roles.*, settings.* ----
        $manager = Role::where('name', 'manager')->first();
        $managerPerms = Permission::whereNotIn('module', ['users', 'roles', 'settings'])->pluck('id');
        $manager->permissions()->sync($managerPerms);

        // ---- CASHIER: dashboard + sales.* only ----
        $cashier = Role::where('name', 'cashier')->first();
        $cashierPerms = Permission::whereIn('name', [
            'dashboard.view',
            'sales.view',
            'sales.create',
        ])->pluck('id');
        $cashier->permissions()->sync($cashierPerms);

        // ---- ACCOUNTANT: dashboard + reports + expenses ----
        $accountant = Role::where('name', 'accountant')->first();
        $accountantPerms = Permission::whereIn('name', [
            'dashboard.view',
            'reports.view',
            'expenses.view',
            'expenses.manage',
        ])->pluck('id');
        $accountant->permissions()->sync($accountantPerms);

        // ============================================
        // 4. MIGRATE EXISTING USERS (role string → role_id)
        // ============================================
        $roleMap = [
            'admin'   => $admin->id,
            'manager' => $manager->id,
            'cashier' => $cashier->id,
        ];

        // Old column 'role' may still exist. If yes, migrate.
        if (\Schema::hasColumn('users', 'role')) {
            $users = User::all();
            foreach ($users as $user) {
                if (empty($user->role_id)) {
                    $roleName = $user->role ?? 'cashier';
                    $user->role_id = $roleMap[$roleName] ?? $cashier->id;
                    $user->save();
                }
            }
        } else {
            // Fallback — assign cashier to any user without role_id (safer than admin)
            User::whereNull('role_id')->update(['role_id' => $cashier->id]);
        }
    }
}