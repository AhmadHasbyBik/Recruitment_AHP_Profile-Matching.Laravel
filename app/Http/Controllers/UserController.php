<?php
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ProfileMatchingResult;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = [
            'super_admin' => 'Super Admin',
            'hrd' => 'HR Department',
            'direktur' => 'Direktur',
            'user' => 'Regular User'
        ];

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:super_admin,hrd,direktur,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = [
            'super_admin' => 'Super Admin',
            'hrd' => 'HR Department',
            'direktur' => 'Direktur',
            'user' => 'Regular User'
        ];

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,hrd,direktur,user',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user_id = Auth::user()->id;

        if ($user->id === $user_id) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete yourself.');
        }

        DB::transaction(function () use ($user) {
            // Hapus profile matching results yang diproses oleh user ini
            ProfileMatchingResult::where('processed_by', $user->id)->delete();

            // Hapus interview schedules dan results yang dimiliki user ini
            $user->interviewSchedules()->each(function ($schedule) {
                $schedule->result()->delete();
                $schedule->delete();
            });

            // Hapus candidates yang dimiliki user ini dan data terkait
            $user->candidates()->each(function ($candidate) {
                $candidate->criteriaValues()->delete();
                $candidate->results()->delete();
                $candidate->interviewSchedules()->delete();
                $candidate->delete();
            });

            // Terakhir hapus user
            $user->delete();
        });

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Password changed successfully.');
    }
}
