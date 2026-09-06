<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { 
    LayoutGrid,
     Users,
     List,
     CalendarDays,
     FileText,
     Images,
     UserRoundCog,
     Mail,
     BarChart,
     Settings,
     UserCircle,
     PlusCircle,
     ListTodo,
     ClipboardList,
 } from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Alumni',
        href: '#',
        icon: Users,
        items: [
            {
                title: 'Alumni List',
                href: '/alumni',
                icon: List,
            },
            {
                title: 'Create Alumni',
                href: '/alumni/create',
                icon: PlusCircle,
            },
            {
                title: 'Pending Alumni',
                href: '/alumni/pending',
                icon: ListTodo,
            }
        ],
    },

    {
        title: 'Blogs',
        href: '#',
        icon: FileText,
        items: [
            {
                title: 'Blog List',
                href: '/blog',
            },
            {
                title: 'Create Blog',
                href: '/blog/create',
                icon: List,
            },
            {
                title: 'Edit Blog',
                href: '/blog/edit',
            }
        ],
    },
    {
        title: 'Events',
        href: '#',
        icon: CalendarDays,
        items: [
            {
                title: 'Event List',
                href: '/event',
                icon:List,
            },
            {
                title: 'Create Event',
                href: '/event/create',
                icon: PlusCircle,
            },
            {
                title: 'Edit Event',
                href: '/event/edit',

            }
        ]
    },
    {
        title: 'Registrations',
        href: '#',
        icon: Users,
        items: [
            {
                title: 'Registration List',
                href: '/registration',
            },
            {
                title: 'Pending Registration',
                href: '/registration/create',
                icon: List,
            },
        ]
    },
    {
        title: 'Gallery',
        href: '#',
        icon: Images,
    },
    {
        title: 'Committee',
        href: '#',
        icon: UserRoundCog,
    },
    {
        title: 'Messages',
        href: '#',
        icon: Mail,
    },
    {
        title: 'Report',
        href: '#',
        icon: BarChart,
    },
    {
        title: 'Settings',
        href: '#',
        icon: Settings,
    },
    {
        title: 'Profile',
        href: '/profile',
        icon: UserCircle,
    },



];

const footerNavItems: NavItem[] = [
    
    // {
    //     title: 'Repository',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: FolderGit2,
    // },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
