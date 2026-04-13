<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Box, User } from 'lucide-vue-next';
import { route } from 'ziggy-js';
import AppLogo from '@/components/AppLogo.vue';
import NavUser from '@/components/NavUser.vue';
import type { SidebarProps } from '@/components/ui/sidebar';
import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
    SidebarRail,
    SidebarFooter,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';

const props = defineProps<SidebarProps>();

const data = {
    navMain: [
        {
            title: 'Dashboard',
            route: 'dashboard',
            icon: LayoutGrid,
        },
        {
            title: 'Products',
            icon: Box,
            items: [
                {
                    title: 'Manage Products',
                    route: 'products.index',
                },
                {
                    title: 'Manage Brands',
                    route: 'brands.index',
                },
            ],
        },
        {
            title: 'Users',
            icon: User,
            items: [
                {
                    title: 'Manage Users',
                    route: 'users.index',
                },
            ],
        },
    ],
};
</script>

<template>
    <Sidebar v-bind="props" collapsible="icon" variant="floating">
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
            <SidebarGroup>
                <SidebarMenu>
                    <SidebarMenuItem
                        v-for="item in data.navMain"
                        :key="item.title"
                        :class="{
                            'bg-sidebar-accent':
                                item.route && route().current(item.route),
                        }"
                    >
                        <SidebarMenuButton as-child>
                            <Link :href="item.route ? route(item.route) : '#'">
                                <component :is="item.icon" class="size-4" />
                                {{ item.title }}
                            </Link>
                        </SidebarMenuButton>

                        <SidebarMenuSub v-if="item.items?.length">
                            <SidebarMenuSubItem
                                v-for="childItem in item.items"
                                :key="childItem.title"
                            >
                                <SidebarMenuSubButton
                                    as-child
                                    :is-active="
                                        route().current(childItem.route)
                                    "
                                >
                                    <Link :href="route(childItem.route)">
                                        {{ childItem.title }}
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
        </SidebarContent>
        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
