<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ElderProfile;
use App\Models\ServiceProviderProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $role = $request->input('role');
        if (! $role) {
            $role = ($request->has('conduct_document') || $request->has('id_number')) ? 'provider' : 'elder';
        }

        $user = null;

        if ($role === 'provider') {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone' => ['nullable', 'string', 'max:30', 'required_without:phone_number'],
                'phone_number' => ['nullable', 'string', 'max:30', 'required_without:phone'],
                'dob' => [
                    'required',
                    'date',
                    'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
                ],
                'id_number' => ['nullable', 'string', 'max:50'],
                'id_document' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:5120'],
                'conduct_document' => ['required', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:5120'],
            ], [
                'dob.before_or_equal' => 'يجب ألا يقل عمر مقدم الخدمة عن 18 عاماً.',
                'dob.required' => 'تاريخ الميلاد مطلوب.',
                'phone.required_without' => 'رقم الهاتف مطلوب.',
                'phone_number.required_without' => 'رقم الهاتف مطلوب.',
                'id_document.required' => 'صورة الهوية الشخصية مطلوبة.',
                'conduct_document.required' => 'شهادة حسن السيرة والسلوك مطلوبة.',
            ]);

            $phoneNumber = $request->input('phone_number') ?? $request->input('phone');

            DB::transaction(function () use ($validated, $request, $phoneNumber, &$user) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'status' => 'pending',
                ]);

                $idDocPath = $request->file('id_document')->store('documents/ids', 'local');
                $conductCertPath = $request->file('conduct_document')->store('documents/certificates', 'local');

                ServiceProviderProfile::create([
                    'user_id' => $user->id,
                    'full_name' => $validated['name'],
                    'id_number' => $request->input('id_number'),
                    'birth_date' => $validated['dob'],
                    'phone_number' => $phoneNumber,
                    'id_document_path' => $idDocPath,
                    'good_conduct_cert_path' => $conductCertPath,
                    'tier' => 1,
                    'completed_tasks_count' => 0,
                    'average_rating' => 0,
                    'is_available' => true,
                    'reliability_incidents_count' => 0,
                ]);
            });
        } else {
            $hasStrictElderFields = $request->has('role') || $request->has('city');

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone' => [$hasStrictElderFields ? 'required_without:phone_number' : 'nullable', 'string', 'max:30'],
                'phone_number' => [$hasStrictElderFields ? 'required_without:phone' : 'nullable', 'string', 'max:30'],
                'city' => [$hasStrictElderFields ? 'required' : 'nullable', 'string', 'max:255'],
                'dob' => ['nullable', 'date'],
                'id_document' => ['nullable', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:5120'],
                'profile_photo' => ['nullable', 'image', 'max:5120'],
            ], [
                'phone.required_without' => 'رقم الهاتف مطلوب.',
                'phone_number.required_without' => 'رقم الهاتف مطلوب.',
                'city.required' => 'المدينة مطلوبة.',
            ]);

            $phoneNumber = $request->input('phone_number') ?? $request->input('phone') ?? '0590000000';
            $city = $request->input('city') ?? 'غزة';

            DB::transaction(function () use ($validated, $request, $phoneNumber, $city, &$user) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'status' => 'pending',
                ]);

                if ($request->hasFile('profile_photo')) {
                    $user->profile_picture_path = $request->file('profile_photo')->store('avatars', 'public');
                    $user->save();
                }

                $idDocPath = $request->hasFile('id_document')
                    ? $request->file('id_document')->store('documents/ids', 'local')
                    : null;

                ElderProfile::create([
                    'user_id' => $user->id,
                    'full_name' => $validated['name'],
                    'id_number' => $request->input('id_number'),
                    'birth_date' => $request->input('dob'),
                    'city' => $city,
                    'address' => $request->input('address'),
                    'housing_type' => $request->input('housing_type'),
                    'phone_number' => $phoneNumber,
                    'id_document_path' => $idDocPath,
                ]);
            });
        }

        event(new Registered($user));

        Auth::login($user);

        // بعد التسجيل مباشرة: توجيه لشاشة تأكيد البريد الإلكتروني (verify-email)
        return redirect()->route('verification.notice');
    }
}
