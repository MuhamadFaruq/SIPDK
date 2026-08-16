<?php

namespace App\Http\Controllers;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\UserRepositoryInterface;
use App\Models\Department;
use App\Models\LetterCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MasterDataController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function users()
    {
        $users = $this->userRepository->getPaginatedUsers(15);
        $roles = Role::all();
        $departments = Department::all();

        return view('master.users', compact('users', 'roles', 'departments'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'nip' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        $user = $this->userRepository->createUser($validated);
        $this->auditLogRepository->record(Auth::user(), 'Menambahkan User', 'Master Data', "Menambahkan pengguna baru: {$user->name}");

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'nip' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'phone' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $updatedUser = $this->userRepository->updateUser($user, $validated);
        $this->auditLogRepository->record(Auth::user(), 'Memperbarui User', 'Master Data', "Memperbarui data pengguna: {$updatedUser->name}");

        return redirect()->back()->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        $userName = $user->name;
        $this->userRepository->deleteUser($user);
        $this->auditLogRepository->record(Auth::user(), 'Menghapus User', 'Master Data', "Menghapus data pengguna: {$userName}");

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    public function categories()
    {
        $categories = LetterCategory::withCount('letters')->get();
        return view('master.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:letter_categories,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $cat = LetterCategory::create($validated);
        $this->auditLogRepository->record(Auth::user(), 'Menambahkan Kategori Surat', 'Master Data', "Menambahkan kategori: {$cat->name}");

        return redirect()->back()->with('success', 'Kategori surat berhasil ditambahkan.');
    }
}
