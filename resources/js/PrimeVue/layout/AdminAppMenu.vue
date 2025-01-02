<script setup>
import { ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Home',
        items: [{ label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/admin' }]
    },
    {
        label: 'HỆ THỐNG',
        items: [
            { label: 'Người quản trị', icon: 'pi pi-fw pi-users', to: '/admin/admins' },
            { label: 'Người dùng', icon: 'pi pi-fw pi-user', to: '/admin/users' },
            { label: 'Nhà cung cấp', icon: 'pi pi-fw pi-table', to: '/admin/suppliers' },
            { label: 'Hàng hóa', icon: 'pi pi-fw pi-server', to: '/admin/materials' },
        ]
    },
]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>
