<script setup>
import { computed, ref } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import {
    User,
    Phone,
    MapPin,
    GraduationCap,
    CalendarDays,
    Hash,
    FileText,
    CheckCircle2,
    ArrowRight,
    AlertCircle,
    Clock3,
} from '@lucide/vue';

const page = usePage();

const user = computed(() => page.props.auth?.user);


// Success Secreen 

const showSuccess = ref(false);
const successMessage = ref('');
const countdown = ref(10);


// const successMessage = computed(
//     () => page.props.flash?.success ?? null
// );

// Alumni form 

const form = useForm({
    full_name: user.value?.name ?? '',
    mobile_number: user.value?.phone ?? '',
    address: '',
    batch: '',
    passing_year: '',
    roll_number: '',
    registration_number: '',
});

// Submit Alumni Information
const submit = () => {
    form.post('/alumni-information', {
        preserveScroll: true,
        onSuccess: () => {

            // flass message from server 
            successMessage.value =
                page.props.flash?.success;
            'ধন্যবাদ! আপনার Alumni তথ্য সফলভাবে জমা হয়েছে।';

            // Succes Screen show 
            showSuccess.value = true;
            countdown.value = 10;

            const timer = setInterval(() => {
                countdown.value--;

                if (countdown.value === 0) {
                    clearInterval(timer);

                    // after 3sec redirect to account status

                    router.visit('/account-status');
                }
            }, 1000);
        },
    });
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-3xl">

            <!-- ============================= -->
            <!-- SUCCESS SCREEN -->
            <!-- ============================= -->
            <div v-if="showSuccess" class="mx-auto max-w-2xl">
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl">

                    <!-- Success Header -->
                    <div class="bg-linear-to-br from-emerald-50 via-white to-blue-50 px-6 py-10 text-center sm:px-10">

                        <!-- Success Icon -->
                        <div class="mb-6 flex justify-center">
                            <div
                                class="flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100 ring-8 ring-emerald-50">
                                <CheckCircle2 class="h-14 w-14 text-emerald-600" stroke-width="2" />
                            </div>
                        </div>

                        <!-- Title -->
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                            তথ্য সফলভাবে জমা হয়েছে!
                        </h1>

                        <p class="mx-auto mt-3 max-w-lg text-sm leading-7 text-slate-600 sm:text-base">
                            {{ successMessage }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="px-6 py-8 sm:px-10">

                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                            <div class="flex items-start gap-4">

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100">
                                    <Clock3 class="h-5 w-5 text-amber-600" />
                                </div>

                                <div>
                                    <h2 class="font-semibold text-slate-900">
                                        আপনার আবেদন যাচাইয়ের অপেক্ষায়
                                    </h2>

                                    <p class="mt-1.5 text-sm leading-6 text-slate-600">
                                        Admin আপনার Alumni তথ্য যাচাই করার
                                        পর আপনার অ্যাকাউন্ট অনুমোদন করবেন।
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Countdown Redirect -->
<div class="mt-8 text-center">
    <div class="flex flex-col items-center">
        <!-- Countdown Circle -->
        <div
            class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-50 ring-8 ring-blue-50/60"
        >
            <span
                class="text-3xl font-bold tabular-nums text-blue-600"
            >
                {{ countdown }}
            </span>
        </div>

        <p class="mt-4 text-sm font-medium text-slate-600">
            আপনার Account Status page-এ নিয়ে যাওয়া হচ্ছে...
        </p>

        <p class="mt-1 text-xs text-slate-400">
            {{ countdown }} সেকেন্ড পর স্বয়ংক্রিয়ভাবে Redirect হবে
        </p>
    </div>
</div>
                    </div>

                    <!-- Footer -->
                    <div class="border-t border-slate-100 px-6 py-5 text-center sm:px-10">
                        <p class="text-xs text-slate-400">
                            Alumni Association
                        </p>
                    </div>
                </div>
            </div>

            <!-- ============================= -->
            <!-- ALUMNI INFORMATION FORM -->
            <!-- ============================= -->
            <div v-else>

                <!-- Header -->
                <div class="mb-8 text-center">

                    <div
                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                        <GraduationCap class="h-8 w-8" />
                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Alumni Information
                    </h1>

                    <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-slate-600 sm:text-base">
                        Please provide your alumni information to complete
                        your registration.
                    </p>
                </div>

                <!-- Form Card -->
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <!-- Card Header -->
                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-5 sm:px-8">
                        <div class="flex items-start gap-3">

                            <div
                                class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                <FileText class="h-5 w-5" />
                            </div>

                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">
                                    Complete Your Alumni Information
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    Please provide accurate information
                                    according to your academic records.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="px-6 py-7 sm:px-8">

                        <!-- General Error -->
                        <div v-if="form.hasErrors"
                            class="mb-6 flex gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <AlertCircle class="mt-0.5 h-5 w-5 shrink-0" />

                            <div>
                                <p class="font-semibold">
                                    Please check the information below.
                                </p>

                                <p class="mt-1 text-red-600">
                                    Some information is missing or invalid.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                            <!-- Full Name -->
                            <div class="md:col-span-2">

                                <label for="full_name" class="mb-2 block text-sm font-medium text-slate-700">
                                    Full Name
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">

                                    <User
                                        class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                                    <input id="full_name" v-model="form.full_name" type="text" autocomplete="name"
                                        placeholder="Enter your full name"
                                        class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-100':
                                                form.errors.full_name,
                                        }" />
                                </div>

                                <p v-if="form.errors.full_name" class="mt-1.5 text-sm text-red-600">
                                    {{ form.errors.full_name }}
                                </p>
                            </div>

                            <!-- Mobile -->
                            <div>

                                <label for="mobile_number" class="mb-2 block text-sm font-medium text-slate-700">
                                    Mobile Number
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">

                                    <Phone
                                        class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                                    <input id="mobile_number" v-model="form.mobile_number" type="tel" autocomplete="tel"
                                        placeholder="01XXXXXXXXX"
                                        class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-100':
                                                form.errors.mobile_number,
                                        }" />
                                </div>

                                <p v-if="form.errors.mobile_number" class="mt-1.5 text-xs text-slate-500">
                                    Example: 01712345678
                                </p>

                                <p v-if="form.errors.mobile_number" class="mt-1.5 text-sm text-red-600">
                                    {{ form.errors.mobile_number }}
                                </p>
                            </div>

                            <!-- Batch -->
                            <div>

                                <label for="batch" class="mb-2 block text-sm font-medium text-slate-700">
                                    Batch
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">

                                    <GraduationCap
                                        class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                                    <input id="batch" v-model="form.batch" type="text" placeholder="e.g. 2009"
                                        class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-100':
                                                form.errors.batch,
                                        }" />
                                </div>

                                <p v-if="form.errors.batch" class="mt-1.5 text-sm text-red-600">
                                    {{ form.errors.batch }}
                                </p>
                            </div>

                            <!-- Passing Year -->
                            <div>

                                <label for="passing_year" class="mb-2 block text-sm font-medium text-slate-700">
                                    Passing Year
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">

                                    <CalendarDays
                                        class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                                    <input id="passing_year" v-model="form.passing_year" type="text" inputmode="numeric"
                                        placeholder="e.g. 2014"
                                        class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-100':
                                                form.errors.passing_year,
                                        }" />
                                </div>

                                <p v-if="form.errors.passing_year" class="mt-1.5 text-sm text-red-600">
                                    {{ form.errors.passing_year }}
                                </p>
                            </div>

                            <!-- Roll Number -->
                            <div>

                                <label for="roll_number" class="mb-2 block text-sm font-medium text-slate-700">
                                    Roll Number

                                </label>

                                <div class="relative">

                                    <Hash
                                        class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                                    <input id="roll_number" v-model="form.roll_number" type="text"
                                        placeholder="Enter your roll number"
                                        class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-100':
                                                form.errors.roll_number,
                                        }" />
                                </div>

                                <p v-if="form.errors.roll_number" class="mt-1.5 text-sm text-red-600">
                                    {{ form.errors.roll_number }}
                                </p>
                            </div>

                            <!-- Registration Number -->
                            <div>

                                <label for="registration_number" class="mb-2 block text-sm font-medium text-slate-700">
                                    Registration Number
                                </label>

                                <div class="relative">

                                    <FileText
                                        class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />

                                    <input id="registration_number" v-model="form.registration_number" type="text"
                                        placeholder="Enter registration number"
                                        class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-100':
                                                form.errors.registration_number,
                                        }" />
                                </div>

                                <p v-if="form.errors.registration_number" class="mt-1.5 text-sm text-red-600">
                                    {{ form.errors.registration_number }}
                                </p>
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">

                                <label for="address" class="mb-2 block text-sm font-medium text-slate-700">
                                    Present Address
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">

                                    <MapPin
                                        class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-slate-400" />

                                    <textarea id="address" v-model="form.address" rows="4"
                                        placeholder="Enter your current address"
                                        class="w-full resize-none rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-100':
                                                form.errors.address,
                                        }"></textarea>
                                </div>

                                <p v-if="form.errors.address" class="mt-1.5 text-sm text-red-600">
                                    {{ form.errors.address }}
                                </p>
                            </div>
                        </div>

                        <!-- Information Notice -->
                        <div class="mt-7 flex gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4">
                            <CheckCircle2 class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" />

                            <div class="text-sm leading-6 text-blue-800">

                                <p class="font-semibold">
                                    Important
                                </p>

                                <p class="mt-1">
                                    Please make sure all information is
                                    correct. Your information will be reviewed
                                    by an admin before your account is approved.
                                </p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8">

                            <button type="submit" :disabled="form.processing"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">

                                <span v-if="form.processing">
                                    Submitting Information...
                                </span>

                                <span v-else>
                                    Submit Alumni Information
                                </span>

                                <ArrowRight v-if="!form.processing" class="h-4 w-4" />
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <p class="mt-6 text-center text-xs text-slate-500">
                    Your information will be securely reviewed by the Alumni
                    Association admin.
                </p>
            </div>
        </div>
    </div>
</template>