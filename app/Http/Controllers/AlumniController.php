<?php

namespace App\Http\Controllers;

use App\Models\AlumniInfo;
use App\Models\AlumniReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AlumniController extends Controller
{
    /**
     * Display approved Alumni List.
     */
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        $alumni = User::query()
            ->where('role', 'user')
            ->where('status', 'approved')
            ->with('alumniInfo')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('alumniInfo', function ($alumniQuery) use ($search) {
                            $alumniQuery
                                ->where('member_id', 'like', "%{$search}%")
                                ->orWhere('batch', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Alumni/Index', [
            'alumni' => $alumni,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Show the form for creating a new alumnus.
     */
    public function create(): Response
    {
        return Inertia::render('Alumni/Create');
    }

    /**
     * Store a new Alumni account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(?:\+8801|01)[3-9]\d{8}$/',
                'unique:users,phone',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ], [
            'name.required' => 'Please enter the alumni name.',

            'email.email' => 'Please enter a valid email address.',

            'email.unique' => 'This email is already registered.',

            'phone.regex' =>
                'Please enter a valid Bangladesh mobile number.',

            'phone.unique' =>
                'This phone number is already registered.',

            'password.required' =>
                'Please enter a temporary password.',

            'password.min' =>
                'Password must be at least 8 characters.',
        ]);

        // Email অথবা Phone অন্তত একটি লাগবে
        if (
            empty($validated['email']) &&
            empty($validated['phone'])
        ) {
            return back()
                ->withErrors([
                    'email' =>
                        'Email অথবা Phone-এর অন্তত একটি দিতে হবে.',
                ])
                ->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => bcrypt($validated['password']),
            'role' => 'user',
            'status' => 'pending',
        ]);

        return redirect()
            ->route('alumni.pending')
            ->with(
                'success',
                "{$user->name} এর Alumni account সফলভাবে তৈরি হয়েছে।"
            );
    }

    /**
     * Display pending Alumni List.
     */
    public function pending(Request $request): Response
    {
        $search = $request->input('search');

        $alumni = User::query()
            ->where('role', 'user')
            ->where('status', 'pending')
            ->with('alumniInfo')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('alumniInfo', function ($alumniQuery) use ($search) {
                            $alumniQuery
                                ->where('batch', 'like', "%{$search}%")
                                ->orWhere('passing_year', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Alumni/PendingAlumni', [
            'alumni' => $alumni,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Approve a pending Alumni.
     */
    public function approve(User $user)
    {
        if ($user->status !== 'pending') {
            return back()->with(
                'error',
                'This alumni is not pending approval.'
            );
        }

        $alumniInfo = $user->alumniInfo;

        if (!$alumniInfo) {
            return back()->with(
                'error',
                'Alumni information was not found.'
            );
        }

        DB::transaction(function () use ($user, $alumniInfo) {

            /**
             * Get the latest generated Member ID.
             */
            $lastMember = AlumniInfo::query()
                ->whereNotNull('member_id')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            /**
             * Generate global serial number.
             */
            $nextSerial = $lastMember
                ? ((int) substr($lastMember->member_id, -4)) + 1
                : 1;

            /**
             * Get last two digits of batch.
             */
            $batchLastTwoDigits = substr(
                $alumniInfo->batch,
                -2
            );

            /**
             * Create Member ID.
             */
            $memberId = 'ALU-'
                . $batchLastTwoDigits
                . '-'
                . str_pad(
                    $nextSerial,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            /**
             * Save Member ID.
             */
            $alumniInfo->update([
                'member_id' => $memberId,
            ]);

            /**
             * Update User Status.
             */
            $user->update([
                'status' => 'approved',
            ]);

            /**
             * Save Review History.
             */
            AlumniReview::create([
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'action' => 'approved',
                'message' =>
                    'আপনার Alumni আবেদনটি সফলভাবে অনুমোদন করা হয়েছে।',
            ]);
        });

        return back()->with(
            'success',
            "{$user->name} has been approved successfully."
        );
    }

    /**
     * Reject a pending Alumni.
     */
    public function reject(Request $request, User $user)
    {
        if ($user->status !== 'pending') {
            return back()->with(
                'error',
                'This alumni is not pending approval.'
            );
        }

        if (!$user->alumniInfo) {
            return back()->with(
                'error',
                'Alumni information was not found.'
            );
        }

        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'message.required' =>
                'Rejection reason is required.',

            'message.max' =>
                'Rejection reason cannot exceed 2000 characters.',
        ]);

        DB::transaction(function () use ($user, $validated) {

            /**
             * Update User Status.
             */
            $user->update([
                'status' => 'rejected',
            ]);

            /**
             * Save Review History.
             */
            AlumniReview::create([
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'action' => 'rejected',
                'message' => $validated['message'],
            ]);
        });

        return back()->with(
            'success',
            "{$user->name} has been rejected successfully."
        );
    }

    /**
     * Keep Alumni application pending with a message.
     */
    public function keepPending(Request $request, User $user)
    {
        if ($user->status !== 'pending') {
            return back()->with(
                'error',
                'This alumni is not currently pending.'
            );
        }

        if (!$user->alumniInfo) {
            return back()->with(
                'error',
                'Alumni information was not found.'
            );
        }

        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'message.required' =>
                'Pending reason is required.',

            'message.max' =>
                'Pending reason cannot exceed 2000 characters.',
        ]);

        DB::transaction(function () use ($user, $validated) {

            /**
             * Status remains pending.
             *
             * We only create a new review history record.
             */
            AlumniReview::create([
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'action' => 'pending',
                'message' => $validated['message'],
            ]);
        });

        return back()->with(
            'success',
            "{$user->name} has been kept pending."
        );
    }
}