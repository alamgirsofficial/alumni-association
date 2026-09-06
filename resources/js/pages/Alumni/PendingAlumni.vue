<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import {
    Eye,
    Search,
    Users,
    X,
    CheckCircle2,
    XCircle,
    Clock3,
} from '@lucide/vue';
import { computed, ref, watch } from 'vue';
defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Pending Alumni List',
                // href: edit(),
            },
        ],
    },
});

interface AlumniInfo {
    id?: number;
    full_name?: string | null;
    mobile_number?: string | null;
    address?: string | null;
    batch?: string | null;
    passing_year?: string | null;
    roll_number?: string | null;
    registration_number?: string | null;
    profile_photo?: string | null;
}

interface Alumni {
    id: number;
    name: string;
    email: string | null;
    phone: string | null;
    status: 'pending' | 'approved' | 'rejected';
    role: string;
    created_at: string;
    alumni_info?: AlumniInfo | null;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface AlumniPagination {
    data: Alumni[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    links: PaginationLink[];
}

const props = defineProps<{
    alumni: AlumniPagination;
    filters?: {
        search?: string;
    };
}>();

const search = ref(props.filters?.search ?? '');

const showViewModal = ref(false);
const selectedAlumni = ref<Alumni | null>(null);

// for reject functionalities 
const showRejectModal = ref(false);
const rejectReason = ref('');
const rejectingAlumni = ref<Alumni | null>(null);

const openRejectModal = (user: Alumni) => {
    rejectingAlumni.value = user;
    rejectReason.value = '';
    showRejectModal.value = true;
};

const closeRejectModal = () => {
    showRejectModal.value = false;
    rejectingAlumni.value = null;
    rejectReason.value = '';
};




let searchTimer: ReturnType<typeof setTimeout> | null = null;

const hasSearch = computed(() => search.value.trim().length > 0);

const searchAlumni = () => {
    if (searchTimer) {
        clearTimeout(searchTimer);
    }

    searchTimer = setTimeout(() => {
        router.get(
            '/alumni/pending',
            {
                search: search.value || undefined,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    }, 400);
};

watch(search, () => {
    searchAlumni();
});

const clearSearch = () => {
    search.value = '';
};

const goToPage = (url: string | null) => {
    if (!url) {
        return;
    }

    router.get(
        url,
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const viewAlumni = (user: Alumni) => {
    selectedAlumni.value = user;
    showViewModal.value = true;
};

const closeViewModal = () => {
    showViewModal.value = false;
    selectedAlumni.value = null;
};

const approveAlumni = (user: Alumni) => {
    if (!confirm(`${user.name} কে approve করতে চান?`)) {
        return;
    }

    router.patch(
        `/alumni/${user.id}/approve`,
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                closeViewModal();
            },
        },
    );
};

const rejectAlumni = () => {
    if (!rejectingAlumni.value) {
        return;
    }

    router.patch(
        `/alumni/${rejectingAlumni.value.id}/reject`,
        {
            message: rejectReason.value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                closeRejectModal();
                closeViewModal();
            },
        },
    );
};

const formatDate = (date: string) => {
    if (!date) {
        return '—';
    }

    return new Date(date).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const getInitials = (name: string) => {
    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((word) => word.charAt(0).toUpperCase())
        .join('');
};


const showPendingModal = ref(false);
const pendingReason = ref('');
const pendingAlumni = ref<Alumni | null>(null);

const openPendingModal = (user: Alumni) => {
    pendingAlumni.value = user;
    pendingReason.value = '';
    showPendingModal.value = true;
};

const closePendingModal = () => {
    showPendingModal.value = false;
    pendingAlumni.value = null;
    pendingReason.value = '';
};

const keepPending = () => {
    if (!pendingAlumni.value || !pendingReason.value.trim()) {
        return;
    }

    router.patch(
        `/alumni/${pendingAlumni.value.id}/pending`,
        {
            message: pendingReason.value.trim(),
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                closePendingModal();
                closeViewModal();
            },
        },
    );
};



</script>

<template>
    <div class="min-h-screen bg-slate-50 p-4 sm:p-6 lg:p-8">

        <!-- Header -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <Clock3 class="h-5 w-5" />
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                            Pending Alumni
                        </h1>

                        <p class="mt-0.5 text-sm text-slate-500">
                            Admin approval-এর অপেক্ষায় থাকা alumni এখানে দেখানো হচ্ছে।
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total -->
            <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                <p class="text-xs font-medium text-slate-500">
                    Pending Applications
                </p>

                <p class="mt-1 text-xl font-bold text-amber-600">
                    {{ alumni.total }}
                </p>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-5 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="relative max-w-xl">
                <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />

                <input v-model="search" type="text" placeholder="Search by name, email, phone or member ID..."
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-10 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-100" />

                <button v-if="hasSearch" type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-slate-700"
                    @click="clearSearch">
                    <X class="h-4 w-4" />
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-250 text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">
                                #
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-slate-600">
                                Alumni
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-slate-600">
                                Contact
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-slate-600">
                                Batch
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-slate-600">
                                Passing Year
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-slate-600">
                                Registered
                            </th>

                            <th class="px-4 py-3 text-right font-semibold text-slate-600">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        <tr v-for="(user, index) in alumni.data" :key="user.id" class="transition hover:bg-slate-50">
                            <!-- Serial -->
                            <td class="px-4 py-4 text-slate-500">
                                {{
                                    (alumni.current_page - 1) *
                                    alumni.per_page +
                                    index +
                                    1
                                }}
                            </td>

                            <!-- Alumni -->
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-amber-100 font-semibold text-amber-700">
                                        <img v-if="
                                            user.alumni_info?.profile_photo
                                        " :src="user.alumni_info.profile_photo
                                            " :alt="user.name" class="h-full w-full object-cover" />

                                        <span v-else>
                                            {{ getInitials(user.name) }}
                                        </span>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-900">
                                            {{ user.name }}
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            {{ user.role }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="px-4 py-4">
                                <div class="space-y-1">
                                    <p v-if="user.email" class="text-slate-700">
                                        {{ user.email }}
                                    </p>

                                    <p v-if="user.phone" class="text-xs text-slate-500">
                                        {{ user.phone }}
                                    </p>
                                </div>
                            </td>



                            <!-- Batch -->
                            <td class="px-4 py-4">
                                <span v-if="user.alumni_info?.batch" class="font-medium text-slate-700">
                                    {{ user.alumni_info.batch }}
                                </span>

                                <span v-else class="text-slate-400">
                                    —
                                </span>
                            </td>

                            <!-- Passing Year -->
                            <td class="px-4 py-4">
                                <span v-if="user.alumni_info?.passing_year" class="font-medium text-slate-700">
                                    {{ user.alumni_info.passing_year }}
                                </span>

                                <span v-else class="text-slate-400">
                                    —
                                </span>
                            </td>

                            <!-- Registered -->
                            <td class="px-4 py-4 text-slate-500">
                                {{ formatDate(user.created_at) }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-2">
                                    <button type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                                        @click="viewAlumni(user)">
                                        <Eye class="h-4 w-4" />
                                        View
                                    </button>

                                    <!-- <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700"
                                        @click="approveAlumni(user)"
                                    >
                                        <CheckCircle2 class="h-4 w-4" />
                                        Approve
                                    </button> -->

                                    <!-- <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-700"
                                        @click="rejectAlumni(user)"
                                    >
                                        <XCircle class="h-4 w-4" />
                                        Reject
                                    </button> -->
                                </div>
                            </td>
                        </tr>

                        <!-- Empty -->
                        <tr v-if="alumni.data.length === 0">
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <Users class="h-5 w-5" />
                                </div>

                                <h3 class="mt-4 text-sm font-semibold text-slate-900">
                                    No pending alumni
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{
                                        hasSearch
                                            ? 'আপনার search অনুযায়ী কোনো pending alumni পাওয়া যায়নি।'
                                            : 'বর্তমানে কোনো alumni approval-এর অপেক্ষায় নেই।'
                                    }}
                                </p>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="alumni.last_page > 1"
                class="flex flex-col gap-3 border-t border-slate-200 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Showing
                    <span class="font-medium text-slate-700">
                        {{ alumni.from ?? 0 }}
                    </span>
                    -
                    <span class="font-medium text-slate-700">
                        {{ alumni.to ?? 0 }}
                    </span>
                    of
                    <span class="font-medium text-slate-700">
                        {{ alumni.total }}
                    </span>
                    pending alumni
                </p>

                <div class="flex flex-wrap gap-1">
                    <button v-for="(link, index) in alumni.links" :key="index" type="button" :disabled="!link.url"
                        class="min-w-9 rounded-lg px-3 py-2 text-sm font-medium transition disabled:cursor-not-allowed disabled:opacity-40"
                        :class="link.active
                            ? 'bg-amber-600 text-white'
                            : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50'
                            " @click="goToPage(link.url)" v-html="link.label" />
                </div>
            </div>
        </div>

        <!-- View Modal -->
        <div v-if="showViewModal && selectedAlumni"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
            @click.self="closeViewModal">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-2xl">

                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            আবেদনকারীর প্রয়োজনীয় তথ্যগুলো যাচাই করুন
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            নিবন্ধনের জন্য প্রয়োজনীয় তথ্যগুলো সঠিকভাবে প্রদান করা হয়েছে কি না যাচাই করুন।
                        </p>

                        <ul class="mt-4 space-y-2 text-sm text-slate-700">
                            <li class="flex items-center gap-2">
                                <span class="font-medium">•</span>
                                মোবাইল নম্বর প্রদান করা হয়েছে কি?
                            </li>

                            <li class="flex items-center gap-2">
                                <span class="font-medium">•</span>
                                ব্যাচ নম্বর প্রদান করা হয়েছে কি?
                            </li>

                            <li class="flex items-center gap-2">
                                <span class="font-medium">•</span>
                                পাসিং ইয়ার প্রদান করা হয়েছে কি?
                            </li>
                        </ul>

                    </div>


                    <button type="button"
                        class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                        @click="closeViewModal">
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <!-- Details -->
                <div class="px-5 py-6">

                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-full bg-amber-100 text-lg font-bold text-amber-700">
                            <img v-if="
                                selectedAlumni.alumni_info?.profile_photo
                            " :src="selectedAlumni.alumni_info.profile_photo
                                " :alt="selectedAlumni.name" class="h-full w-full object-cover" />

                            <span v-else>
                                {{ getInitials(selectedAlumni.name) }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-slate-900">
                                {{ selectedAlumni.name }}
                            </h3>


                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-medium text-slate-500">
                                Email
                            </p>

                            <p class="mt-1 break-all text-sm font-medium text-slate-900">
                                {{ selectedAlumni.email ?? '—' }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-medium text-slate-500">
                                Phone
                            </p>

                            <p class="mt-1 text-sm font-medium text-slate-900">
                                {{
                                    selectedAlumni.phone ??
                                    selectedAlumni.alumni_info?.mobile_number ??
                                    '—'
                                }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-medium text-slate-500">
                                Batch
                            </p>

                            <p class="mt-1 text-sm font-medium text-slate-900">
                                {{
                                    selectedAlumni.alumni_info?.batch ??
                                    '—'
                                }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-medium text-slate-500">
                                Passing Year
                            </p>

                            <p class="mt-1 text-sm font-medium text-slate-900">
                                {{
                                    selectedAlumni.alumni_info?.passing_year ??
                                    '—'
                                }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-medium text-slate-500">
                                Roll Number
                            </p>

                            <p class="mt-1 text-sm font-medium text-slate-900">
                                {{
                                    selectedAlumni.alumni_info?.roll_number ??
                                    '—'
                                }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-medium text-slate-500">
                                Registration Number
                            </p>

                            <p class="mt-1 text-sm font-medium text-slate-900">
                                {{
                                    selectedAlumni.alumni_info
                                        ?.registration_number ?? '—'
                                }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-medium text-slate-500">
                                Registered
                            </p>

                            <p class="mt-1 text-sm font-medium text-slate-900">
                                {{ formatDate(selectedAlumni.created_at) }}
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="flex flex-wrap justify-end gap-2 border-t border-slate-200 px-5 py-4">
                    <button type="button"
                        class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        @click="closeViewModal">
                        Close
                    </button>

                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700"
                        @click="openRejectModal(selectedAlumni)">
                        <XCircle class="h-4 w-4" />
                        Reject
                    </button>
                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-700 transition hover:bg-amber-100"
                        @click="openPendingModal(selectedAlumni)">
                        <Clock3 class="h-4 w-4" />
                        Keep Pending
                    </button>

                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700"
                        @click="approveAlumni(selectedAlumni)">
                        <CheckCircle2 class="h-4 w-4" />
                        Approve
                    </button>
                </div>

            </div>
        </div>



        <!-- Reject Modal -->
        <div v-if="showRejectModal && rejectingAlumni"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 p-4"
            @click.self="closeRejectModal">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            আবেদন প্রত্যাখ্যান করুন
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ rejectingAlumni.name }}-এর আবেদন প্রত্যাখ্যানের কারণ লিখুন।
                        </p>
                    </div>

                    <button type="button"
                        class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                        @click="closeRejectModal">
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <!-- Body -->
                <div class="px-5 py-5">
                    <label class="text-sm font-semibold text-slate-700">
                        প্রত্যাখ্যানের কারণ
                    </label>

                    <textarea v-model="rejectReason" rows="5"
                        placeholder="যেমন: ব্যাচ নম্বর বা পাসিং ইয়ারের তথ্য সঠিকভাবে প্রদান করা হয়নি..."
                        class="mt-2 w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-100"></textarea>

                    <p class="mt-2 text-xs text-slate-500">
                        এই বার্তাটি পরবর্তীতে আবেদনকারী তার Account Status পেজে দেখতে পারবেন।
                    </p>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-2 border-t border-slate-200 px-5 py-4">
                    <button type="button"
                        class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        @click="closeRejectModal">
                        বাতিল
                    </button>

                    <button type="button" :disabled="!rejectReason.trim()"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50"
                        @click="rejectAlumni">
                        <XCircle class="h-4 w-4" />
                        আবেদন প্রত্যাখ্যান
                    </button>
                </div>
            </div>
        </div>

        <!-- Keep Pending Modal -->
<div
    v-if="showPendingModal && pendingAlumni"
    class="fixed inset-0 z-60 flex items-center justify-center bg-slate-900/50 p-4"
    @click.self="closePendingModal"
>
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">

        <!-- Header -->
        <div
            class="flex items-center justify-between border-b border-slate-200 px-5 py-4"
        >
            <div>
                <h2 class="text-lg font-bold text-slate-900">
                    আবেদনটি Pending রাখুন
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    {{ pendingAlumni.name }}-এর আবেদনটি কেন pending রাখা হচ্ছে
                    তা লিখুন।
                </p>
            </div>

            <button
                type="button"
                class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                @click="closePendingModal"
            >
                <X class="h-5 w-5" />
            </button>
        </div>

        <!-- Body -->
        <div class="px-5 py-5">

            <div
                class="mb-4 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4"
            >
                <Clock3
                    class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"
                />

                <div>
                    <h3 class="text-sm font-semibold text-amber-800">
                        Application will remain pending
                    </h3>

                    <p class="mt-1 text-xs leading-5 text-amber-700">
                        Alumni-এর status পরিবর্তন হবে না। আপনার message
                        review history-তে সংরক্ষিত হবে এবং applicant তার
                        Account Status পেজে এটি দেখতে পারবে।
                    </p>
                </div>
            </div>

            <label
                for="pending-reason"
                class="text-sm font-semibold text-slate-700"
            >
                Pending রাখার কারণ
            </label>

            <textarea
                id="pending-reason"
                v-model="pendingReason"
                rows="5"
                maxlength="2000"
                placeholder="যেমন: প্রদত্ত তথ্য যাচাই করা হচ্ছে। অনুগ্রহ করে আরও কিছু সময় অপেক্ষা করুন..."
                class="mt-2 w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-100"
            ></textarea>

            <div class="mt-2 flex items-center justify-between">
                <p class="text-xs text-slate-500">
                    এই message applicant দেখতে পারবেন।
                </p>

                <span class="text-xs text-slate-400">
                    {{ pendingReason.length }}/2000
                </span>
            </div>
        </div>

        <!-- Footer -->
        <div
            class="flex justify-end gap-2 border-t border-slate-200 px-5 py-4"
        >
            <button
                type="button"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                @click="closePendingModal"
            >
                Cancel
            </button>

            <button
                type="button"
                :disabled="!pendingReason.trim()"
                class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700 disabled:cursor-not-allowed disabled:opacity-50"
                @click="keepPending"
            >
                <Clock3 class="h-4 w-4" />
                Keep Pending
            </button>
        </div>
    </div>
</div>
    </div>
</template>