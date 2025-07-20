<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    public function index()
    {
        return CustomerResource::collection(Customer::latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => [
                'required',
                'email',
                Rule::unique('customers')->where(fn($q) =>
                    $q->where('client_id', $user->id)
                ),
            ],
            'phone'     => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('customers')->where(fn($q) =>
                    $q->where('client_id', $user->id)
                ),
            ],
            'address'   => 'nullable|string',
        ]);
        $validated['client_id'] = $user->id;

        $customer = Customer::create($validated);

        return new CustomerResource($customer);
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    public function update(Request $request, Customer $customer)
    {
        $user = Auth::guard('api')->user();

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => [
                'required',
                'email',
                    Rule::unique('customers')->ignore($customer->id)
                    ->where(fn($q) => $q->where('client_id', $user->id)),
            ],
            'phone'   => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('customers')->ignore($customer->id)
                    ->where(fn($q) => $q->where('client_id', $user->id)),
            ],
            'address' => 'nullable|string',
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
