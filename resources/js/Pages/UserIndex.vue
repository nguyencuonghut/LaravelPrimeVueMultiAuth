<template>
    <Head>
        <title>Người dùng</title>
    </Head>

    <div class="card">
        <DataTable v-model:filters="filters" v-model:selection="selectedUsers" :value="users" paginator :rows="10" dataKey="id" filterDisplay="menu"
            :globalFilterFields="['name', 'email', 'role', 'status']">
            <template #header>
                <div class="flex justify-between">
                    <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined @click="clearFilter()" />
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Tìm kiếm" />
                    </IconField>
                </div>
            </template>
            <template #empty> Không tìm thấy người dùng. </template>
            <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
            <Column field="name" header="Tên" sortable style="min-width: 14rem">
                <template #body="{ data }">
                    {{ data.name }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Tìm theo tên" />
                </template>
            </Column>
            <Column field="email" header="Email" sortable style="min-width: 14rem">
                <template #body="{ data }">
                    {{ data.email }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Tìm theo email" />
                </template>
            </Column>
            <Column header="Vai trò" field="role" sortable :filterMenuStyle="{ width: '14rem' }" style="min-width: 12rem">
                <template #body="{ data }">
                    <Tag :value="data.role" :severity="getRoleSeverity(data.role)" />
                </template>
                <template #filter="{ filterModel }">
                    <Select v-model="filterModel.value" :options="roles" placeholder="Chọn" showClear>
                        <template #option="slotProps">
                            <Tag :value="slotProps.option" :severity="getRoleSeverity(slotProps.option)" />
                        </template>
                    </Select>
                </template>
            </Column>
            <Column header="Trạng thái" field="status" sortable :filterMenuStyle="{ width: '14rem' }" style="min-width: 12rem">
                <template #body="{ data }">
                    <Tag :value="data.status" :severity="getStatusSeverity(data.status)" />
                </template>
                <template #filter="{ filterModel }">
                    <Select v-model="filterModel.value" :options="statuses" placeholder="Chọn" showClear>
                        <template #option="slotProps">
                            <Tag :value="slotProps.option" :severity="getStatusSeverity(slotProps.option)" />
                        </template>
                    </Select>
                </template>
            </Column>
            <Column :exportable="false" style="min-width: 12rem">
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editUser(slotProps.data)" />
                    <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteUser(slotProps.data)" />
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';

defineProps({
    errors: {
        type: Object,
    },
    users: Object,
});

const selectedUsers = ref();
const filters = ref();
const roles = ref(['Quản trị', 'Người dùng']);
const statuses = ref(['On', 'Off']);

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        name: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        email: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        role: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
        status: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
    };
};

initFilters();

const clearFilter = () => {
    initFilters();
};

const getStatusSeverity = (status) => {
    switch (status) {
        case 'On':
            return 'success';

        case 'Off':
            return 'danger';
    }
};

const getRoleSeverity = (status) => {
    switch (status) {
        case 'Người quản trị':
            return 'success';

        case 'Người dùng':
            return 'info';
    }
};
</script>
