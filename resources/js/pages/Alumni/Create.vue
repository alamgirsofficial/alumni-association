<!-- resources/js/pages/Alumni/Create.vue -->
<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    CheckCircle2,
    Eye,
    EyeOff,
    KeyRound,
    Mail,
    Phone,
    UserPlus,
    UserRound,
} from '@lucide/vue';
defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Alumni Create',
                // href: edit(),
            },
        ],
    },
});

const showPassword = ref(false);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
});

const hasEmail = computed(() => form.email.trim() !== '');
const hasPhone = computed(() => form.phone.trim() !== '');

const submit = () => {
    form.post('/alumni', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showPassword.value = false;
        },
    });
};
</script>

<template>
    <Head title="Create Alumni" />

    <div class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">

            <!-- Header -->
            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <div class="mb-2 flex items-center gap-2">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600"
                        >
                            <UserPlus class="h-5 w-5" />
                        </div>

                        <div>
                            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">
                                Create Alumni Account
                            </h1>

                            <p class="text-sm text-slate-500">
                                Admin can create an account for an Alumni.
                            </p>
                        </div>
                    </div>
                </div>

                <Link
                    href="/alumni"
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
                >
                    <ArrowLeft class="h-4 w-4" />
                    <span class="hidden sm:inline">Back</span>
                </Link>
            </div>

            <!-- Information Notice -->
            <div
                class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-4"
            >
                <div class="flex gap-3">
                    <div
                        class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600"
                    >
                        <KeyRound class="h-4 w-4" />
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-blue-900">
                            How this works
                        </h3>

                        <p class="mt-1 text-sm leading-6 text-blue-700">
                            এই form থেকে account তৈরি করার পর Alumni এই
                            credentials দিয়ে login করতে পারবে। Login করার পর
                            তাকে Alumni Information সম্পূর্ণ করতে হবে।
                            Information submit করার পর account টি Admin
                            approval-এর জন্য Pending list-এ যাবে।
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >
                <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                    <h2 class="font-semibold text-slate-900">
                        Account Information
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Only basic login information is required here.
                    </p>
                </div>

                <form
                    @submit.prevent="submit"
                    class="space-y-6 px-5 py-6 sm:px-6"
                >
                    <!-- Name -->
                    <div>
                        <label
                            for="name"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Full Name
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <UserRound
                                class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                            />

                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Enter alumni full name"
                                autocomplete="name"
                                class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                :class="{
                                    'border-red-300 focus:border-red-500 focus:ring-red-100':
                                        form.errors.name,
                                }"
                            />
                        </div>

                        <p
                            v-if="form.errors.name"
                            class="mt-1.5 text-xs text-red-600"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Email + Phone -->
                    <div class="grid gap-5 md:grid-cols-2">

                        <!-- Email -->
                        <div>
                            <label
                                for="email"
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                Email
                            </label>

                            <div class="relative">
                                <Mail
                                    class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                />

                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="alumni@example.com"
                                    autocomplete="email"
                                    class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                    :class="{
                                        'border-red-300 focus:border-red-500 focus:ring-red-100':
                                            form.errors.email,
                                    }"
                                />
                            </div>

                            <p
                                v-if="form.errors.email"
                                class="mt-1.5 text-xs text-red-600"
                            >
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label
                                for="phone"
                                class="mb-2 block text-sm font-medium text-slate-700"
                            >
                                Phone
                            </label>

                            <div class="relative">
                                <Phone
                                    class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                />

                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    type="text"
                                    placeholder="01XXXXXXXXX"
                                    autocomplete="tel"
                                    class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                    :class="{
                                        'border-red-300 focus:border-red-500 focus:ring-red-100':
                                            form.errors.phone,
                                    }"
                                />
                            </div>

                            <p
                                v-if="form.errors.phone"
                                class="mt-1.5 text-xs text-red-600"
                            >
                                {{ form.errors.phone }}
                            </p>
                        </div>
                    </div>

                    <!-- Login Method Info -->
                    <div
                        class="rounded-xl border border-slate-200 bg-slate-50 p-4"
                    >
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-slate-500 shadow-sm"
                            >
                                <UserRound class="h-4 w-4" />
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-800">
                                    Login information
                                </p>

                                <p class="mt-1 text-xs leading-5 text-slate-500">
                                    Email অথবা Phone-এর অন্তত একটি দিতে হবে।
                                    Alumni পরে এই তথ্য ব্যবহার করে login করতে
                                    পারবে।
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label
                            for="password"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Temporary Password
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <KeyRound
                                class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                            />

                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="Enter temporary password"
                                autocomplete="new-password"
                                class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-11 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                :class="{
                                    'border-red-300 focus:border-red-500 focus:ring-red-100':
                                        form.errors.password,
                                }"
                            />

                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-slate-600"
                            >
                                <EyeOff
                                    v-if="showPassword"
                                    class="h-4 w-4"
                                />

                                <Eye
                                    v-else
                                    class="h-4 w-4"
                                />
                            </button>
                        </div>

                        <p class="mt-1.5 text-xs text-slate-500">
                            এই password Alumni-কে জানিয়ে দিতে হবে।
                        </p>

                        <p
                            v-if="form.errors.password"
                            class="mt-1.5 text-xs text-red-600"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Account Status Preview -->
                    <div
                        class="rounded-xl border border-amber-200 bg-amber-50 p-4"
                    >
                        <div class="flex items-start gap-3">
                            <CheckCircle2
                                class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"
                            />

                            <div>
                                <p class="text-sm font-semibold text-amber-900">
                                    Account status after creation
                                </p>

                                <p class="mt-1 text-xs leading-5 text-amber-700">
                                    Account তৈরি হবে
                                    <strong>Pending</strong> status-এ।
                                    Member ID এখন তৈরি হবে না। Alumni
                                    Information submit করার পর Admin approve
                                    করলে Member ID generate হবে।
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div
                        class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end"
                    >
                        <Link
                            href="/alumni"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Cancel
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <UserPlus class="h-4 w-4" />

                            <span>
                                {{
                                    form.processing
                                        ? 'Creating...'
                                        : 'Create Alumni Account'
                                }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


