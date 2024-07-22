<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function generateCode($role)
    {
        $prefix = '';
        switch ($role) {
            case 'admin':
                $prefix = 'A';
                break;
            case 'sales':
                $prefix = 'S';
                break;
            case 'customer':
                $prefix = 'C';
                break;
            default:
                // Handle other cases or errors
                break;
        }

        // Generate the next number for the code
        $latestUser = User::where('role', $role)->orderBy('created_at', 'desc')->first();
        if ($latestUser) {
            $lastNumber = (int) substr($latestUser->code, 1);
            $number = $lastNumber + 1;
        } else {
            $number = 1;
        }

        // Ensure the generated code is unique
        $code = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);

        $existingCode = User::where('kode', $code)->exists();
        if ($existingCode) {
            return $this->generateCode($role);
        }

        return $code;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'telp' => 'required|string|max:15',
            'role' => 'required|string|in:admin,sales,customer',
        ]);

        $kode = $this->generateCode($request->input('role'));

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'telp' => $request->input('telp'),
            'role' => $request->input('role'),
            'password' => $request->input('password') ? Hash::make($request->input('password')) : null,
            'kode' => $kode,
        ]);

        // Redirect or respond with success message
        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $password = $request->input('password') ? Hash::make($request->input('password')) : $user->password;

        if ($request->input('role') === 'customer') {
            $password = null;
        }

        // Lakukan update data pengguna
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'telp' => $request->input('telp'),
            'role' => $request->input('role'),
            'password' => $password,
        ]);

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::destroy($id);
        return back()->with('success', 'User deleted successfully.');
    }
}
