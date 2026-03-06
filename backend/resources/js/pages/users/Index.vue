<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { usePoll } from '@inertiajs/vue3';
import { Pencil, Plus, Trash } from 'lucide-vue-next';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardFooter,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { User } from '@/types/index';

usePoll(2000);

interface PaginatedUsers {
    data: User[];
    meta: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    links: { url: string | null; label: string; active: boolean }[];
}

defineProps<{ users: PaginatedUsers }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users', href: route('users.index') },
];

const deleteUser = (id: number) => {
    router.delete(route('users.destroy', id), {
        preserveScroll: true,
    });
};

const goToPage = (page: number) => {
    router.get(
        route('users.index'),
        { page },
        { preserveScroll: true, preserveState: true },
    );
};
</script>

<template>
    <Head title="Users" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-4">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <div>
                        <CardTitle class="text-xl text-primary"
                            >Users</CardTitle
                        >
                        <CardDescription
                            ><p class="text-sm text-muted-foreground">
                                Manage all of {{ users.meta.total }} users here
                            </p></CardDescription
                        >
                    </div>
                    <Link :href="route('users.create')">
                        <Button> <Plus class="h-2 w-4" /> Add Users</Button>
                    </Link>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>S.N</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Roles</TableHead>
                                <TableHead class="text-right"
                                    >Actions</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="(user, index) in users.data"
                                :key="user.id"
                            >
                                <TableCell class="text-muted-foreground">
                                    {{
                                        (users.meta.current_page - 1) *
                                            users.meta.per_page +
                                        index +
                                        1
                                    }}
                                </TableCell>
                                <TableCell class="font-medium">{{
                                    user.name
                                }}</TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                                <TableCell>
                                    <span
                                        v-for="r in user.roles"
                                        :key="r"
                                        class="mr-1 rounded-full bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary"
                                    >
                                        {{ r }}
                                    </span>
                                </TableCell>
                                <TableCell class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <Link
                                            :href="route('users.edit', user.id)"
                                        >
                                            <Button variant="ghost" size="sm"
                                                ><Pencil
                                                    class="h-4 w-4 text-shadow-green-800"
                                            /></Button>
                                        </Link>
                                        <Dialog>
                                            <DialogTrigger as-child>
                                                <Button
                                                    variant="destructive"
                                                    size="sm"
                                                    ><Trash
                                                /></Button>
                                            </DialogTrigger>
                                            <DialogContent
                                                class="sm:max-w-[425px]"
                                            >
                                                <DialogHeader>
                                                    <DialogTitle
                                                        >Delete User
                                                        ?</DialogTitle
                                                    >
                                                    <DialogDescription>
                                                        Are you sure you want to
                                                        delete this user
                                                        permanently. This cannot
                                                        be undone.
                                                    </DialogDescription>
                                                </DialogHeader>
                                                <DialogFooter>
                                                    <DialogClose as-child>
                                                        <Button
                                                            variant="secondary"
                                                        >
                                                            Cancel
                                                        </Button>
                                                    </DialogClose>
                                                    <Button
                                                        variant="destructive"
                                                        size="sm"
                                                        @click="
                                                            deleteUser(user.id)
                                                        "
                                                    >
                                                        <Trash />
                                                        Delete
                                                    </Button>
                                                </DialogFooter>
                                            </DialogContent>
                                        </Dialog>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
                <CardFooter class="flex justify-center">
                    <div
                        class="flex w-full max-w-md flex-wrap justify-center gap-2"
                    >
                        <Pagination
                            :total="users.meta.total"
                            :items-per-page="users.meta.per_page"
                            :page="users.meta.current_page"
                            :sibling-count="1"
                            show-edges
                            @update:page="goToPage"
                        >
                            <PaginationContent v-slot="{ items }">
                                <PaginationPrevious />
                                <template
                                    v-for="item in items"
                                    :key="
                                        item.type === 'page'
                                            ? item.value
                                            : item.type
                                    "
                                >
                                    <PaginationItem
                                        v-if="item.type === 'page'"
                                        :value="item.value"
                                        as-child
                                    >
                                        <Button
                                            :variant="
                                                item.value ===
                                                users.meta.current_page
                                                    ? 'default'
                                                    : 'outline'
                                            "
                                            size="sm"
                                            class="w-9"
                                            @click="goToPage(item.value)"
                                        >
                                            {{ item.value }}
                                        </Button>
                                    </PaginationItem>
                                    <PaginationEllipsis
                                        v-else-if="item.type === 'ellipsis'"
                                    />
                                </template>

                                <PaginationNext />
                            </PaginationContent>
                        </Pagination>
                    </div>
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>
