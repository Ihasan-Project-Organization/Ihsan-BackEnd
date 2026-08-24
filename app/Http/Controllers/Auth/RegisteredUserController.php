<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => ['required', Rule::in(['elderly', 'volunteer'])],
            'dob' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:30'],
            'id_number' => ['required_if:account_type,volunteer', 'nullable', 'string', 'max:255', 'unique:registration_profiles,identity_number'],
            'city' => ['required_if:account_type,elderly', 'nullable', 'string', 'max:255'],
            'address' => ['required_if:account_type,elderly', 'nullable', 'string', 'max:1000'],
            'housing_type' => ['required_if:account_type,elderly', 'nullable', Rule::in(['apartment', 'house', 'family'])],
            'extra_info' => ['nullable', 'string', 'max:2000'],
            'id_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120', 'required_if:account_type,volunteer'],
        ]);

        $documentPaths = [
            'identity' => $request->file('id_document')?->store('registration-documents/identity', 'public'),
            'conduct' => $request->file('conduct_document')?->store('registration-documents/conduct', 'public'),
            'profile_photo' => $request->file('profile_photo')?->store('registration-documents/personal-photos', 'public'),
        ];

        try {
            $user = DB::transaction(function () use ($validated, $documentPaths) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'account_type' => $validated['account_type'],
                ]);

                $user->registrationProfile()->create([
                    'date_of_birth' => $validated['dob'],
                    'phone' => $validated['phone'],
                    'identity_number' => $validated['id_number'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'housing_type' => $validated['housing_type'] ?? null,
                    'extra_info' => $validated['extra_info'] ?? null,
                    'identity_document_path' => $documentPaths['identity'],
                    'profile_photo_path' => $documentPaths['profile_photo'],
                ]);

                return $user;
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete(array_filter($documentPaths));

            throw $exception;
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
