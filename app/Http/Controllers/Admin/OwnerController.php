<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOwnerRequest;
use App\Http\Requests\Admin\UpdateOwnerRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class OwnerController extends Controller
{
    public function index(): Response
    {
        $owners = User::where('role', User::ROLE_SHOP_OWNER)
            ->paginate(15);

        return Inertia::render('Admin/Owners/Index', [
            'owners' => $owners,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Owners/Create');
    }

    public function store(StoreOwnerRequest $request): RedirectResponse
    {
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => User::ROLE_SHOP_OWNER,
        ]);

        return redirect()->route('admin.owners.index')
            ->with('success', 'Shop owner created successfully.');
    }

    public function edit(User $owner): RedirectResponse|Response
    {
        abort_unless($owner->isShopOwner(), 404);

        return Inertia::render('Admin/Owners/Edit', [
            'owner' => $owner,
        ]);
    }

    public function update(UpdateOwnerRequest $request, User $owner): RedirectResponse
    {
        abort_unless($owner->isShopOwner(), 404);

        $data = $request->validated();

        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $owner->update($data);

        return redirect()->route('admin.owners.index')
            ->with('success', 'Shop owner updated successfully.');
    }

    public function destroy(User $owner): RedirectResponse
    {
        abort_unless($owner->isShopOwner(), 404);

        $owner->delete();

        return redirect()->route('admin.owners.index')
            ->with('success', 'Shop owner deleted successfully.');
    }
}
