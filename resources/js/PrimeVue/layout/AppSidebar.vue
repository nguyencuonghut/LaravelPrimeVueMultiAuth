<script setup>
import AppMenu from './AppMenu.vue';
import AdminAppMenu from './AdminAppMenu.vue';
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage();
const is_admin = computed(() => page.props.is_admin);
</script>

<template>
    <div class="layout-sidebar">
        <admin-app-menu v-if="is_admin"></admin-app-menu>
        <app-menu v-else></app-menu>
    </div>
</template>

<style lang="scss" scoped></style>
