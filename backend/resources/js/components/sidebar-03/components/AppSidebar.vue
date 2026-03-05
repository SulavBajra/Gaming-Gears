<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LayoutGrid } from 'lucide-vue-next';
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
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';

const props = defineProps<SidebarProps>();

const data = {
    navMain: [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Products',
            icon: LayoutGrid,
            items: [
                {
                    title: 'Manage Products',
                    href: route('products.index'),
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
                    >
                        <SidebarMenuButton as-child>
                            <Link :href="item.href" class="font-medium">
                                <component :is="item.icon" class="size-4" />
                                {{ item.title }}
                            </Link>
                        </SidebarMenuButton>
                        <SidebarMenuSub v-if="item.items?.length">
                            <SidebarMenuSubItem
                                v-for="childItem in item.items"
                                :key="childItem.title"
                            >
                                <SidebarMenuSubButton as-child>
                                    <Link :href="childItem.href">
                                        {{ childItem.title }}
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
        </SidebarContent>
        <SidebarRail />
        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>
