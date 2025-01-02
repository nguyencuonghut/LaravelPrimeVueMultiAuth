<template>
    <Head>
        <title>Nhà cung cấp</title>
    </Head>

    <div class="card">
        <Toolbar  v-if="can.create_supplier || can.delete_supplier || can.import_supplier || can.export_supplier" class="mb-6">
            <template #start>
                <Button v-if="can.create_supplier" label="New" icon="pi pi-plus" class="mr-2" @click="openNew" />
                <Button v-if="can.delete_supplier" label="Delete" icon="pi pi-trash" severity="danger" outlined @click="confirmDeleteSelected" :disabled="!selectedSuppliers || !selectedSuppliers.length" />
            </template>

            <template #end>
                <FileUpload v-if="can.import_supplier" mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" customUpload chooseLabel="Import" class="mr-2" auto :chooseButtonProps="{ severity: 'secondary' }" />
                <Button v-if="can.export_supplier" label="Export" icon="pi pi-upload" severity="secondary" @click="exportCSV($event)" />
            </template>
        </Toolbar>

        <DataTable ref="dt" v-model:filters="filters" v-model:selection="selectedSuppliers" :value="suppliers" paginator :rows="10" dataKey="id" filterDisplay="menu"
            :globalFilterFields="['code', 'name', 'users', 'status']"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 25]"
            currentPageReportTemplate="Hiển thị từ {first} đến {last} trên tổng số {totalRecords} nhà cung cấp"
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
            <template #empty> Không tìm thấy nhà cung cấp. </template>
            <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
            <Column field="code" header="Mã" sortable style="min-width: 14rem">
                <template #body="{ data }">
                    {{ data.code }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Tìm theo mã" />
                </template>
            </Column>
            <Column field="name" header="Tên" sortable style="min-width: 14rem">
                <template #body="{ data }">
                    {{ data.name }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Tìm theo tên" />
                </template>
            </Column>
            <Column field="users" header="Tài khoản" sortable style="min-width: 14rem">
                <template #body="{ data }">
                    {{ data.users }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Tìm theo tài khoản" />
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
            <Column  v-if="can.update_supplier || can.delete_supplier" :exportable="false" style="min-width: 12rem">
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editSupplier(slotProps.data)" />
                    <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteSupplier(slotProps.data)" />
                </template>
            </Column>
        </DataTable>


        <Dialog v-model:visible="supplierDialog" :style="{ width: '450px' }" header="Chi tiết nhà cung cấp" :modal="true">
            <div class="flex flex-col gap-6">
                <div>
                    <label for="name" class="block font-bold mb-3 required-field">Mã</label>
                    <InputText id="code" v-model="form.code" @change="form.validate('code')" autofocus :invalid="submitted && !form.code" fluid />
                    <small v-if="form.invalid('code')" class="text-red-500">{{ form.errors.code }}</small>
                </div>
                <div>
                    <label for="name" class="block font-bold mb-3 required-field">Tên</label>
                    <InputText id="name" v-model="form.name" @change="form.validate('name')" autofocus :invalid="submitted && !form.name" fluid />
                    <small v-if="form.invalid('name')" class="text-red-500">{{ form.errors.name }}</small>
                </div>
                <div>
                    <span class="block font-bold mb-4 required-field">Trạng thái</span>
                    <div class="grid grid-cols-12 gap-4">
                        <div class="flex items-center gap-2 col-span-6">
                            <RadioButton id="status_on" v-model="form.status" value="On" name="status"/>
                            <label for="status_on">ON</label>
                        </div>
                        <div class="flex items-center gap-2 col-span-6">
                            <RadioButton id="status_off" v-model="form.status" value="Off" name="status"/>
                            <label for="status_off">OFF</label>
                        </div>
                    </div>
                    <small v-if="form.invalid('status')" class="text-red-500">{{ form.errors.status }}</small>
                </div>
            </div>

            <template #footer>
                <Button label="Hủy" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Lưu" icon="pi pi-check" :disabled="form.hasErrors" @click="saveSupplier" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteSupplierDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="form"
                    >Bạn chắc chắn muốn xóa <b>{{ form.name }}</b
                    >?</span
                >
            </div>
            <template #footer>
                <Button label="Không" icon="pi pi-times" text @click="deleteSupplierDialog = false" />
                <Button label="Có" icon="pi pi-check" @click="deleteSupplier" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteSuppliersDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="form">Bạn chắc chắn muốn xóa những NCC đã chọn?</span>
            </div>
            <template #footer>
                <Button label="Không" icon="pi pi-times" text @click="deleteSuppliersDialog = false" />
                <Button label="Có" icon="pi pi-check" text @click="deleteSelectedSuppliers" />
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
    suppliers: Object,
    can: Object,
});

const supplierDialog = ref(false);
const deleteSupplierDialog = ref(false);
const deleteSuppliersDialog = ref(false);
const form = useForm('post', '/admin/suppliers', {
    id: '',
    code: '',
    name: '',
    status: '',
});
const submitted = ref(false);
const isAddSupplier = ref(false);

const openNew = () => {
    form.reset();
    submitted.value = false;
    supplierDialog.value = true;
    isAddSupplier.value = true;
};

const hideDialog = () => {
    supplierDialog.value = false;
    submitted.value = false;
};

const saveSupplier = () => {
    submitted.value = true;

    // Add new Supplier
    if (isAddSupplier.value) {
        // Creat new Supplier
        form.submit({
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                supplierDialog.value = false;
                toast.add({severity:'success', summary: 'Thành công', detail: message, life: 3000});
            },
            onError: () => {
                toast.add({severity: 'error', summary: 'Lỗi', detail: message, life: 3000});
            },
        });
    } else {
        // Edit this Supplier
        form.put(`/admin/suppliers/${form.id}`, {
            onSuccess: () => {
                form.reset();
                supplierDialog.value = false;
                toast.add({severity: 'success', summary: 'Thành công', detail: message, life: 3000});
            },
            onError: () => {
                toast.add({severity: 'error', summary: 'Lỗi', detail: message, life: 3000});
            },
        });

    }
};
const editSupplier = (supplier) => {
    setSupplier(supplier);
    supplierDialog.value = true;
    isAddSupplier.value = false;
};
const confirmDeleteSupplier = (supplier) => {
    setSupplier(supplier);
    deleteSupplierDialog.value = true;
};

const deleteSupplier = () => {
    form.delete(`/admin/suppliers/${form.id}`, {
        onSuccess: () => {
            form.reset();
            deleteSupplierDialog.value = false;
            toast.add({severity:'success', summary: 'Successful', detail: message, life: 3000});
        },
        onError: () => {
            deleteSupplierDialog.value = false;
            toast.add({severity:'error', summary: 'Failed', detail: message, life: 3000});
        },
    });
};

const setSupplier = (supplier) => {
    form.id = supplier.id;
    form.code = supplier.code;
    form.name = supplier.name;
    form.status = supplier.status;
}

const exportCSV = () => {
    dt.value.exportCSV();
};
const confirmDeleteSelected = () => {
    deleteSuppliersDialog.value = true;
};

const deleteSelectedSuppliers = () => {
    router.post('/admin/suppliers/bulkDelete', {suppliers : selectedSuppliers.value }, {
        onSuccess: () => {
            deleteSuppliersDialog.value = false;
            selectedSuppliers.value = null;
            toast.add({severity:'success', summary: 'Successful', detail: message, life: 3000});
        },
        onError: () => {
            deleteSuppliersDialog.value = false;
            selectedSuppliers.value = null;
            toast.add({severity:'error', summary: 'Failed', detail: message, life: 3000});
        },
    });
};

const selectedSuppliers = ref();
const filters = ref();
const statuses = ref(['On', 'Off']);

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        code: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        name: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        users: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
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
</script>
