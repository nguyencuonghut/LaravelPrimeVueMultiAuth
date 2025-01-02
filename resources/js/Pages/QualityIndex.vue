<template>
    <Head>
        <title>Tiêu chuẩn chất lượng</title>
    </Head>

    <div class="card">
        <Toolbar  v-if="can.create_quality || can.delete_quality || can.import_quality || can.export_quality" class="mb-6">
            <template #start>
                <Button v-if="can.create_quality" label="New" icon="pi pi-plus" class="mr-2" @click="openNew" />
                <Button v-if="can.delete_quality" label="Delete" icon="pi pi-trash" severity="danger" outlined @click="confirmDeleteSelected" :disabled="!selectedQualities || !selectedQualities.length" />
            </template>

            <template #end>
                <FileUpload v-if="can.import_quality" mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" customUpload chooseLabel="Import" class="mr-2" auto :chooseButtonProps="{ severity: 'secondary' }" />
                <Button v-if="can.export_quality" label="Export" icon="pi pi-upload" severity="secondary" @click="exportCSV($event)" />
            </template>
        </Toolbar>

        <DataTable ref="dt" v-model:filters="filters" v-model:selection="selectedQualities" :value="qualities" paginator :rows="10" dataKey="id" filterDisplay="menu"
            :globalFilterFields="['material', 'detail', 'status']"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 25]"
            currentPageReportTemplate="Hiển thị từ {first} đến {last} trên tổng số {totalRecords} tiêu chuẩn chất lượng"
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
            <template #empty> Không tìm thấy tiêu chuẩn chất lượng. </template>
            <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
            <Column field="material" header="Nguyên liệu" sortable style="min-width: 14rem">
                <template #body="{ data }">
                    {{ data.material }}
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Tìm theo nguyên liệu" />
                </template>
            </Column>
            <Column field="detail" header="Chi tiết" sortable style="min-width: 14rem">
                <template #body="{ data }">
                    <div style="white-space: pre-line;">{{ data.detail }}</div>
                </template>
                <template #filter="{ filterModel }">
                    <InputText v-model="filterModel.value" type="text" placeholder="Tìm theo chi tiết" />
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
            <Column  v-if="can.update_quality || can.delete_quality" :exportable="false" style="min-width: 12rem">
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editQuality(slotProps.data)" />
                    <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteQuality(slotProps.data)" />
                </template>
            </Column>
        </DataTable>


        <Dialog v-model:visible="qualityDialog" :style="{ width: '450px' }" header="Chi tiết hàng hóa" :modal="true">
            <div class="flex flex-col gap-6">
                <div>
                    <label for="material" class="block font-bold mb-3 required-field">Công ty</label>
                    <Select v-model="form.material" @change="form.validate('material')" :options="materials" filter class="w-full" placeholder="Chọn nguyên liệu" />
                    <small v-if="form.invalid('material')" class="text-red-500">{{ form.errors.material }}</small>
                </div>
                <div>
                    <label for="detail" class="block font-bold mb-3 required-field">Chi tiết</label>
                    <Textarea id="detail" v-model="form.detail" @change="form.validate('detail')" autoResize rows="5" cols="30" autofocus :invalid="submitted && !form.detail" fluid />
                    <small v-if="form.invalid('detail')" class="text-red-500">{{ form.errors.detail }}</small>
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
                <Button label="Lưu" icon="pi pi-check" :disabled="form.hasErrors" @click="saveQuality" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteQualityDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="form"
                    >Bạn chắc chắn muốn xóa <b>{{ form.name }}</b
                    >?</span
                >
            </div>
            <template #footer>
                <Button label="Không" icon="pi pi-times" text @click="deleteQualityDialog = false" />
                <Button label="Có" icon="pi pi-check" @click="deleteQuality" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteQualitiesDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="form">Bạn chắc chắn muốn xóa những hàng hóa đã chọn?</span>
            </div>
            <template #footer>
                <Button label="Không" icon="pi pi-times" text @click="deleteQualitiesDialog = false" />
                <Button label="Có" icon="pi pi-check" text @click="deleteSelectedQualities" />
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
    qualities: Object,
    can: Object,
    materials: Object,
});

const qualityDialog = ref(false);
const deleteQualityDialog = ref(false);
const deleteQualitiesDialog = ref(false);
const form = useForm('post', '/admin/qualities', {
    id: '',
    material: '',
    detail: '',
    status: '',
});
const submitted = ref(false);
const isAddQuality = ref(false);

const openNew = () => {
    form.reset();
    submitted.value = false;
    qualityDialog.value = true;
    isAddQuality.value = true;
};

const hideDialog = () => {
    qualityDialog.value = false;
    submitted.value = false;
};

const saveQuality = () => {
    submitted.value = true;

    // Add new Quality
    if (isAddQuality.value) {
        // Creat new Quality
        form.submit({
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                qualityDialog.value = false;
                toast.add({severity:'success', summary: 'Thành công', detail: message, life: 3000});
            },
            onError: () => {
                toast.add({severity: 'error', summary: 'Lỗi', detail: message, life: 3000});
            },
        });
    } else {
        // Edit this Quality
        form.put(`/admin/qualities/${form.id}`, {
            onSuccess: () => {
                form.reset();
                qualityDialog.value = false;
                toast.add({severity: 'success', summary: 'Thành công', detail: message, life: 3000});
            },
            onError: () => {
                toast.add({severity: 'error', summary: 'Lỗi', detail: message, life: 3000});
            },
        });

    }
};
const editQuality = (quality) => {
    setQuality(quality);
    qualityDialog.value = true;
    isAddQuality.value = false;
};
const confirmDeleteQuality = (quality) => {
    setQuality(quality);
    deleteQualityDialog.value = true;
};

const deleteQuality = () => {
    form.delete(`/admin/qualities/${form.id}`, {
        onSuccess: () => {
            form.reset();
            deleteQualityDialog.value = false;
            toast.add({severity:'success', summary: 'Successful', detail: message, life: 3000});
        },
        onError: () => {
            deleteQualityDialog.value = false;
            toast.add({severity:'error', summary: 'Failed', detail: message, life: 3000});
        },
    });
};

const setQuality = (quality) => {
    form.id = quality.id;
    form.material = quality.material;
    form.detail = quality.detail;
    form.status = quality.status;
}

const exportCSV = () => {
    dt.value.exportCSV();
};
const confirmDeleteSelected = () => {
    deleteQualitiesDialog.value = true;
};

const deleteSelectedQualities = () => {
    router.post('/admin/qualities/bulkDelete', {qualities : selectedQualities.value }, {
        onSuccess: () => {
            deleteQualitiesDialog.value = false;
            selectedQualities.value = null;
            toast.add({severity:'success', summary: 'Successful', detail: message, life: 3000});
        },
        onError: () => {
            deleteQualitiesDialog.value = false;
            selectedQualities.value = null;
            toast.add({severity:'error', summary: 'Failed', detail: message, life: 3000});
        },
    });
};

const selectedQualities = ref();
const filters = ref();
const statuses = ref(['On', 'Off']);

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        material: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        detail: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
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
