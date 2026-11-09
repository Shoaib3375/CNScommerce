<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOwnerRequest;
use App\Http\Requests\Admin\UpdateOwnerRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    public function index(): JsonResponse
    {
        $owners = User::where('role', User::ROLE_SHOP_OWNER)->paginate(15);

        return response()->json($owners);
    }

    public function store(StoreOwnerRequest $request): JsonResponse
    {
        $owner = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_SHOP_OWNER,
        ]);

        return response()->json($owner, 201);
    }

    public function edit(User $owner): JsonResponse
    {
        abort_unless($owner->isShopOwner(), 404);

        return response()->json(['owner' => $owner]);
    }

    public function update(UpdateOwnerRequest $request, User $owner): JsonResponse
    {
        abort_unless($owner->isShopOwner(), 404);

        $data = $request->validated();

        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $owner->update($data);

        return response()->json($owner);
    }

    public function destroy(User $owner): JsonResponse
    {
        abort_unless($owner->isShopOwner(), 404);

        $owner->delete();

        return response()->json(null, 204);
    }
}
