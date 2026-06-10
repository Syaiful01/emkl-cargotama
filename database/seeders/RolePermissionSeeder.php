<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Permissions
        $permissions = [
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'module' => 'Dashboard'],
            ['name' => 'View Customers', 'slug' => 'customer.view', 'module' => 'Customer'],
            ['name' => 'Create Customer', 'slug' => 'customer.create', 'module' => 'Customer'],
            ['name' => 'Edit Customer', 'slug' => 'customer.edit', 'module' => 'Customer'],
            ['name' => 'Delete Customer', 'slug' => 'customer.delete', 'module' => 'Customer'],
            ['name' => 'View Shipments', 'slug' => 'shipment.view', 'module' => 'Shipment'],
            ['name' => 'Create Shipment', 'slug' => 'shipment.create', 'module' => 'Shipment'],
            ['name' => 'Edit Shipment', 'slug' => 'shipment.edit', 'module' => 'Shipment'],
            ['name' => 'Delete Shipment', 'slug' => 'shipment.delete', 'module' => 'Shipment'],
            ['name' => 'View Documents', 'slug' => 'document.view', 'module' => 'Document'],
            ['name' => 'Upload Document', 'slug' => 'document.upload', 'module' => 'Document'],
            ['name' => 'Download Document', 'slug' => 'document.download', 'module' => 'Document'],
            ['name' => 'View Invoices', 'slug' => 'invoice.view', 'module' => 'Invoice'],
            ['name' => 'Create Invoice', 'slug' => 'invoice.create', 'module' => 'Invoice'],
            ['name' => 'Print Invoice', 'slug' => 'invoice.pdf', 'module' => 'Invoice'],
            ['name' => 'View Receivables', 'slug' => 'receivable.view', 'module' => 'Receivable'],
            ['name' => 'Record Payment', 'slug' => 'receivable.payment', 'module' => 'Receivable'],
            ['name' => 'View Reports', 'slug' => 'report.view', 'module' => 'Report'],
            ['name' => 'Manage Users', 'slug' => 'user.manage', 'module' => 'User'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 2. Create Roles
        $adminRole = Role::updateOrCreate(['slug' => 'admin'], [
            'name' => 'Administrator',
            'description' => 'Full access to all system modules.'
        ]);

        $financeRole = Role::updateOrCreate(['slug' => 'finance'], [
            'name' => 'Finance',
            'description' => 'Handles invoices, receivables, and financial reports.'
        ]);

        $opsRole = Role::updateOrCreate(['slug' => 'operations'], [
            'name' => 'Operations',
            'description' => 'Handles shipments and documents.'
        ]);

        $managerRole = Role::updateOrCreate(['slug' => 'manager'], [
            'name' => 'Manager',
            'description' => 'Monitoring and high-level reporting.'
        ]);

        // 3. Assign Permissions to Roles
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        $financeRole->permissions()->sync(
            Permission::whereIn('module', ['Dashboard', 'Customer', 'Invoice', 'Receivable', 'Report'])->pluck('id')
        );

        $opsRole->permissions()->sync(
            Permission::whereIn('module', ['Dashboard', 'Customer', 'Shipment', 'Document', 'Report'])->pluck('id')
        );

        $managerRole->permissions()->sync(
            Permission::whereIn('module', ['Dashboard', 'Report'])->pluck('id')
        );

        // 4. Create Default Admin User
        User::updateOrCreate(['email' => 'admin@emkl.com'], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'status' => 'active'
        ]);
    }
}
