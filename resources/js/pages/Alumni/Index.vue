<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3';
import {
    Eye,
    Search,
    Users,
    X,
    ArrowRight
} from '@lucide/vue';
import { computed, ref, watch } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Alumni List',
                // href: edit(),
            },
        ],
    },
});

interface AlumniInfo {
    id?: number;
    registration_number?: string | null;
    member_id?: string | null;
    batch?: string | null;
    designation?: string | null;
    company?: string | null;
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

let searchTimer: ReturnType<typeof setTimeout> | null = null;

const hasSearch = computed(() => search.value.trim().length > 0);

const searchAlumni = () => {
    if (searchTimer) {
        clearTimeout(searchTimer);
    }

    searchTimer = setTimeout(() => {
        router.get(
            '/alumni',
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
</script>

<template>
    <div class="min-h-screen bg-slate-50 p-4 sm:p-6 lg:p-8">
        <!-- Header -->
        <div
            class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
        >
            <div>
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-600"
                    >
                        <Users class="h-5 w-5" />
                    </div>

                    <div>
                        <h1
                            class="text-2xl font-bold tracking-tight text-slate-900"
                        >
                           Registered Alumni : <span> {{ alumni.total }}</span>
                        </h1>

                        <p class="mt-0.5 text-sm text-slate-500">
                            সকল approved alumni এখানে দেখানো হচ্ছে।
                        </p>
                    </div>
                </div>
            </div>

            <!-- Create Buuton -->
            <Link
                    href="/alumni/create"
                    class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
                >
                <span class="hidden sm:inline">Create Alumni</span>
                <ArrowRight class="h-4 w-4" />
                </Link>
        </div>

        <!-- Search -->
        <div
            class="mb-5 rounded-xl border border-slate-200 bg-white p-4 shadow-sm"
        >
            <div class="relative max-w-xl">
                <Search
                    class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                />

                <input
                    v-model="search"
                    type="text"
                    placeholder="Search by name, email or phone..."
                    class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-10 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                />

                <button
                    v-if="hasSearch"
                    type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-slate-700"
                    @click="clearSearch"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>
        </div>

        <!-- Table -->
        <div
            class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
        >
            <div class="overflow-x-auto">
                <table class="w-full min-w-225 text-sm">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left font-semibold text-slate-600"
                            >
                                #
                            </th>

                            <th
                                class="px-4 py-3 text-left font-semibold text-slate-600"
                            >
                                Alumni
                            </th>

                            <th
                                class="px-4 py-3 text-left font-semibold text-slate-600"
                            >
                                Contact
                            </th>

                            <th
                                class="px-4 py-3 text-left font-semibold text-slate-600"
                            >
                                Member ID
                            </th>

                            <th
                                class="px-4 py-3 text-left font-semibold text-slate-600"
                            >
                                Batch
                            </th>

                            <th
                                class="px-4 py-3 text-left font-semibold text-slate-600"
                            >
                                Status
                            </th>                          

                            <th
                                class="px-4 py-3 text-left font-semibold text-slate-600"
                            >
                                Registered
                            </th>

                            <th
                                class="px-4 py-3 text-right font-semibold text-slate-600"
                            >
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        <tr
                            v-for="(user, index) in alumni.data"
                            :key="user.id"
                            class="transition hover:bg-slate-50"
                        >
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
                                        class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-100 font-semibold text-blue-700"
                                    >
                                        <img
                                            v-if="
                                                user.alumni_info?.profile_photo
                                            "
                                            :src="
                                                user.alumni_info.profile_photo
                                            "
                                            :alt="user.name"
                                            class="h-full w-full object-cover"
                                        />

                                        <span v-else>
                                            {{ getInitials(user.name) }}
                                        </span>
                                    </div>

                                    <div class="min-w-0">
                                        <p
                                            class="truncate font-semibold text-slate-900"
                                        >
                                            {{ user.name }}
                                        </p>

                                        <p
                                            v-if="
                                                user.alumni_info?.designation
                                            "
                                            class="truncate text-xs text-slate-500"
                                        >
                                            {{
                                                user.alumni_info.designation
                                            }}
                                            <span
                                                v-if="
                                                    user.alumni_info?.company
                                                "
                                            >
                                                ·
                                                {{
                                                    user.alumni_info.company
                                                }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="px-4 py-4">
                                <div class="space-y-1">
                                    <p
                                        v-if="user.email"
                                        class="text-slate-700"
                                    >
                                        {{ user.email }}
                                    </p>

                                    <p
                                        v-if="user.phone"
                                        class="text-xs text-slate-500"
                                    >
                                        {{ user.phone }}
                                    </p>

                                    <span
                                        v-if="!user.email && !user.phone"
                                        class="text-slate-400"
                                    >
                                        —
                                    </span>
                                </div>
                            </td>

                            <!-- Member ID -->
                            <td class="px-4 py-4">
                                <span
                                    v-if="user.alumni_info?.member_id"
                                    class="rounded-md bg-slate-100 px-2.5 py-1 font-mono text-xs font-medium text-slate-700"
                                >
                                    {{ user.alumni_info.member_id }}
                                </span>

                                <span v-else class="text-slate-400">
                                    —
                                </span>
                            </td>

                            <!-- Batch -->
                            <td class="px-4 py-4">
                                <span
                                    v-if="user.alumni_info?.batch"
                                    class="font-medium text-slate-700"
                                >
                                    {{ user.alumni_info.batch }}
                                </span>

                                <span v-else class="text-slate-400">
                                    —
                                </span>
                            </td>

                            <!-- Status -->
                            
                         <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold"
                                    :class="{
                                        'bg-emerald-100 text-emerald-700':
                                            user.status === 'approved',
                                        'bg-amber-100 text-amber-700':
                                            user.status === 'pending',
                                        'bg-red-100 text-red-700':
                                            user.status === 'rejected',
                                    }"
                                >
                                    {{
                                        user.status === 'approved'
                                            ? 'Approved'
                                            : user.status === 'pending'
                                              ? 'Pending'
                                              : 'Rejected'
                                    }}
                                </span>
                            </td>

                            <!-- Registered -->
                            <td class="px-4 py-4 text-slate-500">
                                {{ formatDate(user.created_at) }}
                            </td>

                            <!-- Action -->
                            <td class="px-4 py-4 text-right">
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                                    @click="viewAlumni(user)"
                                >
                                    <Eye class="h-4 w-4" />
                                    View
                                </button>
                            </td>
                        </tr>

                        <!-- Empty -->
                        <tr v-if="alumni.data.length === 0">
                            <td
                                colspan="8"
                                class="px-6 py-16 text-center"
                            >
                                <div
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400"
                                >
                                    <Users class="h-5 w-5" />
                                </div>

                                <h3
                                    class="mt-4 text-sm font-semibold text-slate-900"
                                >
                                    No alumni found
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{
                                        hasSearch
                                            ? 'আপনার search অনুযায়ী কোনো approved alumni পাওয়া যায়নি।'
                                            : 'এখনও কোনো approved alumni নেই।'
                                    }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                v-if="alumni.last_page > 1"
                class="flex flex-col gap-3 border-t border-slate-200 px-4 py-4 sm:flex-row sm:items-center sm:justify-between"
            >
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
                    alumni
                </p>

                <div class="flex flex-wrap gap-1">
                    <button
                        v-for="(link, index) in alumni.links"
                        :key="index"
                        type="button"
                        :disabled="!link.url"
                        class="min-w-9 rounded-lg px-3 py-2 text-sm font-medium transition disabled:cursor-not-allowed disabled:opacity-40"
                        :class="
                            link.active
                                ? 'bg-blue-600 text-white'
                                : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50'
                        "
                        @click="goToPage(link.url)"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>

        <!-- View Modal -->
        <div
            v-if="showViewModal && selectedAlumni"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
            @click.self="closeViewModal"
        >
            <div
                class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-2xl"
            >
                <!-- Modal Header -->
                <div
                    class="flex items-center justify-between border-b border-slate-200 px-5 py-4"
                >
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Alumni Information
                        </h2>

                        <p class="text-sm text-slate-500">
                            Approved alumni details
                        </p>
                    </div>

                    <button
                        type="button"
                        class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                        @click="closeViewModal"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <!-- Profile -->
                <div class="px-5 py-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-100 text-lg font-bold text-blue-700"
                        >
                            <img
                                v-if="
                                    selectedAlumni.alumni_info?.profile_photo
                                "
                                :src="
                                    selectedAlumni.alumni_info.profile_photo
                                "
                                :alt="selectedAlumni.name"
                                class="h-full w-full object-cover"
                            />

                            <span v-else>
                                {{ getInitials(selectedAlumni.name) }}
                            </span>
                        </div>

                        <div>
                            <h3
                                class="text-xl font-bold text-slate-900"
                            >
                                {{ selectedAlumni.name }}
                            </h3>

                            <p class="text-sm text-slate-500">
                                {{
                                    selectedAlumni.alumni_info?.designation ??
                                    'Alumni'
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div
                            class="rounded-xl bg-slate-50 p-4"
                        >
                            <p class="text-xs font-medium text-slate-500">
                                Email
                            </p>

                            <p
                                class="mt-1 break-all text-sm font-medium text-slate-900"
                            >
                                {{ selectedAlumni.email ?? '—' }}
                            </p>
                        </div>

                        <div
                            class="rounded-xl bg-slate-50 p-4"
                        >
                            <p class="text-xs font-medium text-slate-500">
                                Phone
                            </p>

                            <p
                                class="mt-1 text-sm font-medium text-slate-900"
                            >
                                {{ selectedAlumni.phone ?? '—' }}
                            </p>
                        </div>

                        <div
                            class="rounded-xl bg-slate-50 p-4"
                        >
                            <p class="text-xs font-medium text-slate-500">
                                Registration Number
                            </p>

                            <p
                                class="mt-1 text-sm font-medium text-slate-900"
                            >
                                {{
                                    selectedAlumni.alumni_info
                                        ?.registration_number ?? '—'
                                }}
                            </p>
                        </div>

                        <div
                            class="rounded-xl bg-slate-50 p-4"
                        >
                            <p class="text-xs font-medium text-slate-500">
                                Member ID
                            </p>

                            <p
                                class="mt-1 text-sm font-medium text-slate-900"
                            >
                                {{
                                    selectedAlumni.alumni_info?.member_id ??
                                    '—'
                                }}
                            </p>
                        </div>

                        <div
                            class="rounded-xl bg-slate-50 p-4"
                        >
                            <p class="text-xs font-medium text-slate-500">
                                Batch
                            </p>

                            <p
                                class="mt-1 text-sm font-medium text-slate-900"
                            >
                                {{
                                    selectedAlumni.alumni_info?.batch ?? '—'
                                }}
                            </p>
                        </div>

                        <div
                            class="rounded-xl bg-slate-50 p-4"
                        >
                            <p class="text-xs font-medium text-slate-500">
                                Company
                            </p>

                            <p
                                class="mt-1 text-sm font-medium text-slate-900"
                            >
                                {{
                                    selectedAlumni.alumni_info?.company ??
                                    '—'
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="flex justify-end border-t border-slate-200 px-5 py-4"
                >
                    <button
                        type="button"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                        @click="closeViewModal"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>