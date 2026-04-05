<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomerStoreRequest;
use App\Http\Requests\Api\CustomerUpdateRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use App\Services\CustomerProfileService;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    public function __construct(private readonly CustomerProfileService $customerProfileService) {}

    public function show()
    {
        $user_id = Auth::user()->id;
        $user = User::with([
            'customer:id,user_id,phone,date_of_birth,gender',
            'address',
        ])->findOrFail($user_id);

        if (! $user->customer || ! $user->address) {
            return response()->json(['message' => 'Profile not found']);
        }

        return new UserProfileResource($user);
    }

    public function store(CustomerStoreRequest $request)
    {
        $user = $request->user();
        if ($user->address()->exists() && $user->customer()->exists()) {
            return response()->json([
                'error' => 'Customer profile already exists',
            ], 409);
        }
        $user = $this->customerProfileService->createUserProfile($request);

        return response()->json(['message' => 'Profile Created successfully'], 200);
    }

    public function update(CustomerUpdateRequest $request)
    {
        $user = $request->user();
        $user = $this->customerProfileService->updateUserProfile($request);

        return response()->json(['message' => 'Profile updated successfully'], 200);
    }
}
