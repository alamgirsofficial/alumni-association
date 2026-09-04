<?php

namespace App\Http\Controllers;

use App\Models\AlumniInfo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumniInfoController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        if ($user->alumniInfo && ! session()->has('success')) {
            return redirect()->route('account-status');
        }

        return Inertia::render('User/AlumniInformation');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'mobile_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^(?:\+8801|01)[3-9]\d{8}$/',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            'batch' => [
                'required',
                'string',
                'max:20',
            ],

            'passing_year' => [
                'required',
                'string',
                'max:10',
            ],

            'roll_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'registration_number' => [
                'nullable',
                'string',
                'max:100',
            ],
        ], [
            'full_name.required' =>
            'Please enter your full name.',

            'mobile_number.required' =>
            'Please enter your mobile number.',

            'mobile_number.regex' =>
            'Please enter a valid Bangladesh mobile number.',

            'address.required' =>
            'Please enter your address.',

            'batch.required' =>
            'Please enter your batch.',

            'passing_year.required' =>
            'Please enter your passing year.',
        ]);



        // Create Alumni Information 
        // Member ID will be generated only after Admin approval.
        AlumniInfo::create([
            'user_id' => $user->id,
            'full_name' => $validated['full_name'],
            'mobile_number' => $validated['mobile_number'],
            'address' => $validated['address'],
            'batch' => $validated['batch'],
            'passing_year' => $validated['passing_year'],
            'roll_number' => $validated['roll_number'] ?? null,
            'registration_number' => $validated['registration_number'] ?? null,
        ]);

        return redirect()
            ->route('alumni-information.create')
            ->with(
                'success',
                'ধন্যবাদ! আপনার Alumni তথ্য সফলভাবে জমা হয়েছে। আপনার অ্যাকাউন্টটি বর্তমানে যাচাইয়ের অপেক্ষায় রয়েছে। Admin অনুমোদন করার পর আপনি Dashboard ব্যবহার করতে পারবেন।'
            );
    }
}
