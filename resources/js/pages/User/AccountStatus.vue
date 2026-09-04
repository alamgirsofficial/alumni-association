<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    CheckCircle2,
    Clock3,
    Home,
    GraduationCap,
    XCircle,
} from '@lucide/vue';

interface Review {
    action: 'pending' | 'approved' | 'rejected';
    message: string | null;
    created_at: string;
}

const props = defineProps<{
    status: 'pending' | 'approved' | 'rejected';
    review: Review | null;
}>();
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-10">
        <div class="w-full max-w-lg">

            <!-- Status Icon -->
            <div class="mb-6 flex justify-center">

                <!-- Pending -->
                <div v-if="props.status === 'pending'"
                    class="flex h-20 w-20 items-center justify-center rounded-full bg-amber-100 ring-8 ring-amber-50">
                    <Clock3 class="h-11 w-11 text-amber-600" stroke-width="2" />
                </div>

                <!-- Approved -->
                <div v-else-if="props.status === 'approved'"
                    class="flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 ring-8 ring-emerald-50">
                    <CheckCircle2 class="h-11 w-11 text-emerald-600" stroke-width="2" />
                </div>

                <!-- Rejected -->
                <div v-else
                    class="flex h-20 w-20 items-center justify-center rounded-full bg-red-100 ring-8 ring-red-50">
                    <XCircle class="h-11 w-11 text-red-600" stroke-width="2" />
                </div>
            </div>

            <!-- Card -->
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl">

                <!-- ========================= -->
                <!-- PENDING -->
                <!-- ========================= -->
                <template v-if="props.status === 'pending'">

                    <!-- Header -->
                    <div class="px-6 pb-7 pt-8 text-center sm:px-10">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-sm font-semibold text-amber-700">
                            <Clock3 class="h-4 w-4" />
                            Application Under Review
                        </span>

                        <h1 class="mt-5 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                            Thank You for Registering!
                        </h1>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-7 text-slate-600 sm:text-base">
                            You are one step away from joining with our Alumni
                            Association.
                        </p>
                
                    <!-- Dashboard Notice -->
    
    <div
        class="mt-5 flex gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4"
    >
        <GraduationCap
            class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
        />

        <p
            class="text-sm leading-6 text-blue-800"
        >
            অ্যাডমিন কর্তৃক আপনার অ্যাকাউন্ট অনুমোদিত হয়ে গেলে আপনি আপনার ড্যাশবোর্ডে প্রবেশ করতে পারবেন।
        </p>
    </div>
    </div>

                    

                   <!-- Waiting Status -->
<!-- Waiting Status -->
<div
    class="border-y border-slate-200 bg-slate-50 px-6 py-7 sm:px-10"
>
    <!-- Default Waiting Message -->
    <div
        v-if="
            !props.review?.message ||
            props.review?.action !== 'pending'
        "
        class="flex items-start gap-4"
    >
        <div
            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100"
        >
            <Clock3 class="h-5 w-5 text-amber-600" />
        </div>

        <div>
            <h2
                class="text-base font-semibold text-slate-900"
            >
                Awaiting Admin Approval
            </h2>

            <p
                class="mt-1.5 text-sm leading-6 text-slate-600"
            >
                Your information has been submitted
                successfully and is currently waiting for
                admin approval.
            </p>
        </div>
    </div>

    <!-- Admin Pending Message -->
    <div
        v-if="
            props.review?.message &&
            props.review?.action === 'pending'
        "
        class="group mt-5 rounded-xl border border-amber-200 bg-amber-400 p-4 transition hover:bg-amber-600"
    >
        <div class="flex items-start gap-3">
            <Clock3
                class="mt-0.5 h-5 w-5 shrink-0 text-amber-50"
            />

            <div class="min-w-0">
                <h3
                    class="font-semibold text-amber-800 group-hover:text-amber-50"
                >
                    Admin's Message
                </h3>

                <p
                    class="mt-2 text-sm leading-6 text-amber-700 group-hover:text-amber-50"
                >
                    {{ props.review.message }}
                </p>
            </div>
        </div>
    </div>
</div>




                    <!-- Footer -->
                    <div class="px-6 py-6 sm:px-10">
                        <p class="mb-5 text-center text-sm leading-6 text-slate-500">
                            Your application is currently under review.
                            Please wait for admin approval.
                        </p>

                        <Link href="/"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <Home class="h-4 w-4" />
                            Back to Home
                        </Link>
                    </div>
                </template>


                <!-- ========================= -->
                <!-- APPROVED -->
                <!-- ========================= -->
                <template v-else-if="props.status === 'approved'">

                    <!-- Header -->
                    <div class="px-6 pb-7 pt-8 text-center sm:px-10">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-semibold text-emerald-700">
                            <CheckCircle2 class="h-4 w-4" />
                            Account Approved
                        </span>

                        <h1 class="mt-5 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                            Congratulations!
                        </h1>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-7 text-slate-600 sm:text-base">
                            Your Alumni Association account has been
                            successfully approved.
                        </p>
                    </div>

                    <!-- Approved Status -->
                    <div class="border-y border-slate-200 bg-slate-50 px-6 py-7 sm:px-10">
                        <div class="flex items-start gap-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                            <CheckCircle2 class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" />

                            <div>
                                <h2 class="font-semibold text-emerald-800">
                                    Your account is approved
                                </h2>

                                <p class="mt-1.5 text-sm leading-6 text-emerald-700">
                                    You can now access your Alumni Association
                                    dashboard and use all available member
                                    features.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-6 sm:px-10">
                        <Link href="/dashboard"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            <GraduationCap class="h-4 w-4" />
                            Go to Dashboard
                        </Link>
                    </div>
                </template>


                <!-- ========================= -->
                <!-- REJECTED -->
                <!-- ========================= -->
                <template v-else>

                    <!-- Header -->
                    <div class="px-6 pb-7 pt-8 text-center sm:px-10">
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1.5 text-sm font-semibold text-red-700">
                            <XCircle class="h-4 w-4" />
                            Application Rejected
                        </span>

                        <h1 class="mt-5 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                            Application Not Approved
                        </h1>

                        <p class="mx-auto mt-3 max-w-md text-sm leading-7 text-slate-600 sm:text-base">
                            Unfortunately, your Alumni Association
                            application could not be approved at this time.
                        </p>
                    </div>

                    <!-- Rejection Reason -->
                    <div class="border-y border-slate-200 bg-slate-50 px-6 py-7 sm:px-10">
                        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                            <div class="flex items-start gap-3">
                                <XCircle class="mt-0.5 h-5 w-5 shrink-0 text-red-600" />

                                <div>
                                    <h2 class="font-semibold text-red-800">
                                        Admin's Message
                                    </h2>

                                    <p v-if="props.review?.message" class="mt-2 text-sm leading-6 text-red-700">
                                        {{ props.review.message }}
                                    </p>

                                    <p v-else class="mt-2 text-sm leading-6 text-red-700">
                                        No rejection reason was provided.
                                        Please contact the administrator for
                                        more information.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-6 sm:px-10">
                        <p class="mb-5 text-center text-sm leading-6 text-slate-500">
                            Please review the information and contact the
                            administrator if you believe this decision was
                            made in error.
                        </p>

                        <Link href="/"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <Home class="h-4 w-4" />
                            Back to Home
                        </Link>
                    </div>
                </template>
            </div>

            <!-- Bottom -->
            <p class="mt-6 text-center text-xs text-slate-400">
                Alumni Association
            </p>
        </div>
    </div>
</template>