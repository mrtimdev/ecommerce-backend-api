<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthManager;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {

        return Inertia::render('Admin/Users/Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        if(!auth()->user()->hasRole(['owner'])) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        $request->user()->fill($request->validated());
        $request->user()->name = "$request->first_name $request->last_name";
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        if(!auth()->user()->hasRole(['owner'])) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // AuthManager::logout();

        // $user->delete();

        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
