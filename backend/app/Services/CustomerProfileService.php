<?php

namespace App\Services;

use App\Http\Requests\Api\CustomerStoreRequest;
use App\Http\Requests\Api\CustomerUpdateRequest;
use Illuminate\Support\Facades\DB;

class CustomerProfileService
{
    public function createUserProfile(CustomerStoreRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $user->address()->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'company' => $request->company,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->city,
                'state' => $request->state,
            ]);
            $user->customer()->create([
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
            ]);
        });
    }

    public function updateUserProfile(CustomerUpdateRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $data = $request->validated();

            $user->address()->updateOrCreate(
                ['user_id' => $user->id],
                array_filter([
                    'first_name' => $data['first_name'] ?? null,
                    'last_name' => $data['last_name'] ?? null,
                    'company' => $data['company'] ?? null,
                    'address_line_1' => $data['address_line_1'] ?? null,
                    'address_line_2' => $data['address_line_2'] ?? null,
                    'city' => $data['city'] ?? null,
                    'state' => $data['state'] ?? null,
                ], fn ($v) => ! is_null($v))
            );
            $user->customer()->updateOrCreate(
                ['user_id' => $user->id],
                array_filter([
                    'phone' => $data['phone'] ?? null,
                    'date_of_birth' => $data['date_of_birth'] ?? null,
                    'gender' => $data['gender'] ?? null,
                ], fn ($v) => ! is_null($v))
            );
        });
    }
}
