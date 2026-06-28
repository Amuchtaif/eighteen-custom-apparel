<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the form for editing account settings.
     */
    public function edit()
    {
        $user = auth()->user();
        $whatsappNumber = \App\Models\Setting::getValue('whatsapp_number', '6281234567890');
        return view('settings.edit', compact('user', 'whatsappNumber'));
    }

    /**
     * Update the account settings.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'whatsapp_number' => ['required', 'string', 'max:50'],
        ];

        if ($request->filled('password')) {
            $rules['current_password'] = ['required', 'string'];
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $request->validate($rules);

        if ($request->filled('password')) {
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
            }
            $user->password = Hash::make($request->input('password'));
        }

        $user->update([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
        ]);

        \App\Models\Setting::setValue('whatsapp_number', $request->input('whatsapp_number'));

        return redirect()->route('settings.edit')->with('success', 'Informasi akun dan pengaturan website berhasil diperbarui.');
    }
}
