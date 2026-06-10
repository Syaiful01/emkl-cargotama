<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        $customers = $this->customerService->getAllCustomers($request->all());
        
        if ($request->wantsJson()) {
            return response()->json($customers);
        }

        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:customers',
            'name' => 'required',
            'pic' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'address' => 'nullable',
            'npwp' => 'nullable',
            'status' => 'nullable|in:active,inactive',
        ]);

        $customer = $this->customerService->createCustomer($validated);

        if ($request->wantsJson()) {
            return response()->json($customer, 201);
        }

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $customer = \App\Models\Customer::with('shipments')->findOrFail($id);
        return response()->json($customer);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'sometimes|required|unique:customers,code,' . $id,
            'name' => 'sometimes|required',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $customer = $this->customerService->updateCustomer($id, $validated);
        return response()->json($customer);
    }

    public function destroy($id)
    {
        $this->customerService->deleteCustomer($id);
        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
