<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    public function index()
    {
        return CustomerResource::collection(Customer::latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:users,id',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:customers,email',
            'phone'     => 'nullable|string|max:20|unique:customers,phone',
            'address'   => 'nullable|string',
        ]);

        $customer = Customer::create($validated);

        return new CustomerResource($customer);
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'client_id' => 'sometimes|exists:users,id',
            'name'      => 'sometimes|string|max:255',
            'email'     => [
                'sometimes',
                'email',
                Rule::unique('customers', 'email')->ignore($customer->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('customers', 'phone')->ignore($customer->id),
            ],
            'address'   => 'nullable|string|max:500',
        ]);


        $customer->update($validated);

        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(['success' => true, 'message' => 'Customer deleted']);
    }
}
