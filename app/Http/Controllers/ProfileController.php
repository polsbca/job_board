<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the profile information.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:2048',
        ]);

        if (isset($validated['photo'])) {
            $validated['photo_path'] = $request->file('photo')->store('profile-photos', 'public');
        }

        $request->user()->update($validated);

        return Redirect::route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|string|password',
        ]);

        $request->user()->delete();

        return Redirect::route('login')->with('success', 'Account deleted successfully.');
    }
}
