<template>
    <Head>
        <title>Bộ thầu</title>
    </Head>

    <div class="card">
        <Toolbar  v-if="can.create_tender || can.delete_tender || can.export_tender" class="mb-6">
            <template #start>
                <Button v-if="can.create_tender" label="New" icon="pi pi-plus" class="mr-2" @click="openNew" />
            </template>

            <template #end>
                <Button v-if="can.export_tender" label="Export" icon="pi pi-upload" severity="secondary" @click="exportCSV($event)" />
            </template>
        </Toolbar>

        <DataTable ref="dt" v-model:filters="filters" :value="tenders" paginator :rows="10" dataKey="id" filterDisplay="menu"
            :globalFilterFields="['code', 'title', 'start_time', 'end_time', 'status']"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 25]"
            currentPageReportTemplate="Hiển thị từ {first} đến {last} trên tổng số {totalRecords} bộ thầu"
        >
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
            <template #empty> Không tìm thấy bộ thầu. </template>
            <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
            <Column field="code" header="Mã" sortable style="min-width: 14rem">
                <template #body="{ data }">
                    {{ data.code }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Tìm theo mã" />
                </template>
            </Column>
            <Column field="title" header="Tiêu đề" sortable style="min-width: 14rem">
                <template #body="{ data }">
                    {{ data.title }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Tìm theo tiêu đề" />
                </template>
            </Column>
            <Column field="start_time" header="Thời gian bắt đầu" sortable style="min-width: 14rem">
                <template #body="{ data }">
                    {{ data.start_time }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Tìm theo thời gian bắt đầu" />
                </template>
            </Column>
            <Column field="end_time" header="Thời gian kết thúc" sortable style="min-width: 14rem">
                <template #body="{ data }">
                    {{ data.end_time }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Tìm theo thời gian kết thúc" />
                </template>
            </Column>
            <Column header="Trạng thái" field="status" sortable :filterMenuStyle="{ width: '14rem' }" style="min-width: 5rem">
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
            <Column  v-if="can.update_tender || can.delete_tender" :exportable="false" style="min-width: 10rem">
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editTender(slotProps.data)" />
                    <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteTender(slotProps.data)" />
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="deleteTenderDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="form"
                    >Bạn chắc chắn muốn xóa <b>{{ form.title }}</b
                    >?</span
                >
            </div>
            <template #footer>
                <Button label="Không" icon="pi pi-times" text @click="deleteTenderDialog = false" />
                <Button label="Có" icon="pi pi-check" @click="deleteTender" />
            </template>
        </Dialog>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { FilterMatchMode, FilterOperator } from '@primevue/core/api';
import { useForm } from 'laravel-precognition-vue-inertia';
import { useToast } from 'primevue/usetoast';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const toast = useToast();
const dt = ref();
const page = usePage();
const message = computed(() => page.props.auth.flash.message);

defineProps({
    errors: {
        type: Object,
    },
    tenders: Object,
    can: Object,
});

const tenderDialog = ref(false);
const deleteTenderDialog = ref(false);
const form = useForm('post', 'tenders', {
    id: '',
    title: '',
});
const submitted = ref(false);
const isAddTender = ref(false);

const openNew = () => {
    router.visit('tenders/create');
};

const editTender = (tender) => {
    setTender(tender);
    router.visit('tenders/edit/`$form.id`');
};
const confirmDeleteTender = (tender) => {
    setTender(tender);
    deleteTenderDialog.value = true;
};

const deleteTender = () => {
    form.delete(`tenders/${form.id}`, {
        onSuccess: () => {
            form.reset();
            deleteTenderDialog.value = false;
            toast.add({severity:'success', summary: 'Successful', detail: message, life: 3000});
        },
        onError: () => {
            deleteTenderDialog.value = false;
            toast.add({severity:'error', summary: 'Failed', detail: message, life: 3000});
        },
    });
};

const setTender = (tender) => {
    form.id = tender.id;
    form.title = tender.title;
}

const exportCSV = () => {
    dt.value.exportCSV();
};

const filters = ref();
const statuses = ref(['Mở', 'Đóng', 'Hủy']);

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        code: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        title: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        start_time: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        end_time: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        status: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
    };
};

initFilters();

const clearFilter = () => {
    initFilters();
};

const getStatusSeverity = (status) => {
    switch (status) {
        case 'Mở':
            return 'success';

        case 'Đóng':
            return 'secondary';

        case 'Hủy':
            return 'danger';
    }
};
</script>
