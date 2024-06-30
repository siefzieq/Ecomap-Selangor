<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'position' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . auth()->id(),
            'profile_image_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'department' => 'string|max:255',
            'phone' => 'string|max:12',
        ]);

        $user = auth()->user();
        $user->fill($validated);


        if ($request->hasFile('profile_image_path')) {
            $file = $request->file('profile_image_path');
            $name = time() . '.' . $file->getClientOriginalExtension();

            // Determine the storage path based on the file type
            $storagePath = 'images';
            $filePath = $file->storeAs($storagePath, $name, 'public');

            // Set the file_path attribute to the stored file path
            $user['profile_image_path'] = '/storage/' . $filePath;  // Correct the path concatenation
        }

        if ($user->save()) {
            return redirect()->route('profile')->with('toastr', json_encode([
                'type' => 'success',
                'message' => 'Profile is updated successfully'
            ]));
        } else {
            return redirect()->back()->withInput()->with('toastr', json_encode([
                'type' => 'error',
                'message' => 'Failed to update profile'
            ]));
        }
    }
}
