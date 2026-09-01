
<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronDown } from '@lucide/vue';
import { ref } from 'vue';

import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[];
}>();

const { isCurrentUrl } = useCurrentUrl();

const openMenus = ref<Record<string, boolean>>({});

const toggleMenu = (title: string) => {
    openMenus.value[title] = !openMenus.value[title];
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>

        <SidebarMenu>
            <SidebarMenuItem
                v-for="item in items"
                :key="item.title"
            >
                <!-- Parent menu -->
                <template v-if="item.items?.length">
                    <SidebarMenuButton
                        type="button"
                        :tooltip="item.title"
                        @click="toggleMenu(item.title)"
                    >
                        <component
                            :is="item.icon"
                            class="size-4"
                        />

                        <span>{{ item.title }}</span>

                        <ChevronDown
                            class="ml-auto size-4 transition-transform duration-200"
                            :class="{
                                'rotate-180':
                                    openMenus[item.title],
                            }"
                        />
                    </SidebarMenuButton>

                    <!-- Sub menu -->
                    <div
                        v-if="openMenus[item.title]"
                        class="ml-4 mt-1 space-y-1 border-l pl-2"
                    >
                        <Link
                            v-for="child in item.items"
                            :key="child.title"
                            :href="child.href"
                            class="flex items-center rounded-md px-3 py-2 text-sm text-muted-foreground transition-colors hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
                            :class="{
                                'bg-sidebar-accent text-sidebar-accent-foreground':
                                    isCurrentUrl(child.href),
                            }"
                        >
                            <span>{{ child.title }}</span>
                        </Link>
                    </div>
                </template>

                <!-- Normal menu -->
                <SidebarMenuButton
                    v-else
                    as-child
                    :is-active="isCurrentUrl(item.href)"
                    :tooltip="item.title"
                >
                    <Link :href="item.href">
                        <component
                            :is="item.icon"
                            class="size-4"
                        />

                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
