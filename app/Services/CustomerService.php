<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function getAllCustomers($filters = [])
    {
        $query = Customer::query();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('code', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function createCustomer(array $data)
    {
        return Customer::create($data);
    }

    public function updateCustomer($id, array $data)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);
        return $customer;
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        return $customer->delete();
    }
}
