<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount(['orders', 'entitlements'])
            ->when($request->query('role'), fn ($q, $role) => $q->where('role', $role))
            ->when($search = trim((string) $request->query('search')), function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%");
                });
            })
            ->when(in_array($request->query('sort'), ['name', 'email', 'created_at', 'orders_count', 'entitlements_count'], true), function ($q) use ($request) {
                $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';
                $q->orderBy($request->query('sort'), $direction);
            }, fn ($q) => $q->orderBy('created_at', 'desc'))
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', 'in:admin,user'],
        ]);

        if ($user->id === auth()->id() && $data['role'] !== 'admin') {
            return back()->with('status', 'Anda tidak bisa menurunkan role akun sendiri.');
        }

        $user->update(['role' => $data['role']]);

        return back()->with('status', "Role {$user->name} diubah menjadi {$data['role']}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('status', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('status', "User {$user->name} dihapus.");
    }
}
