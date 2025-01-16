<template>
    <Head>
        <title>Sửa bộ thầu</title>
    </Head>

    <div class="card">
        <Fluid>
            <span class="font-bold mb-8 block">SỬA BỘ THẦU</span>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="title" class="block font-bold mb-3 required-field">Tiêu đề</label>
                    <InputText id="title" v-model="form.title" @change="form.validate('title')" autofocus :invalid="submitted && !form.title" fluid />
                    <small v-if="form.invalid('title')" class="text-red-500">{{ form.errors.title }}</small>
                </div>
                <div>
                    <label for="material" class="block font-bold mb-3 required-field">Hàng hóa</label>
                    <Select v-model="form.material" @change="form.validate('material')" :options="materials" :invalid="form.invalid('material')" filter class="w-full" placeholder="Chọn hàng hóa" />
                    <small v-if="form.invalid('material')" class="text-red-500">{{ form.errors.material }}</small>
                </div>
                <div>
                    <label for="origin" class="block font-bold mb-3">Xuất xứ</label>
                    <InputText id="origin" v-model="form.origin" @change="form.validate('origin')" autofocus fluid />
                </div>
                <div>
                    <label for="packing" class="block font-bold mb-3">Đóng gói</label>
                    <InputText id="packing" v-model="form.packing" @change="form.validate('packing')" autofocus fluid />
                </div>
            </div>


            <div class="grid grid-cols-3 gap-4 mt-6">
                <div>
                    <label for="certificate" class="block font-bold mb-3">Chứng từ</label>
                    <InputText id="certificate" v-model="form.certificate" @change="form.validate('certificate')" autofocus fluid />
                </div>
                <div>
                    <label for="freight_charge" class="block font-bold mb-3">Cước vận tải</label>
                    <InputText id="freight_charge" v-model="form.freight_charge" @change="form.validate('freight_charge')" autofocus fluid />
                </div>
                <div>
                    <label for="other_term" class="block font-bold mb-3">Điều khoản khác</label>
                    <InputText id="other_term" v-model="form.other_term" @change="form.validate('other_term')" autofocus fluid />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-6">
                <div>
                    <label for="delivery_condition" class="block font-bold mb-3 required-field">Giao hàng</label>
                    <Textarea id="delivery_condition" v-model="form.delivery_condition" @change="form.validate('delivery_condition')" autoResize rows="2" cols="30" autofocus :invalid="submitted && !form.delivery_condition" fluid />
                    <small v-if="form.invalid('delivery_condition')" class="text-red-500">{{ form.errors.delivery_condition }}</small>
                </div>
                <div>
                    <label for="payment_condition" class="block font-bold mb-3 required-field">Thanh toán</label>
                    <Textarea id="payment_condition" v-model="form.payment_condition" @change="form.validate('payment_condition')" autoResize rows="2" cols="30" autofocus :invalid="submitted && !form.payment_condition" fluid />
                    <small v-if="form.invalid('payment_condition')" class="text-red-500">{{ form.errors.payment_condition }}</small>
                </div>
                <div>
                    <label for="start_time" class="block font-bold mb-3 required-field">Bắt đầu</label>
                    <DatePicker id="start_time" v-model="form.start_time" showTime hourFormat="24" :invalid="form.invalid('start_time')" dateFormat="dd/mm/yy" fluid />
                    <small v-if="form.invalid('start_time')" class="text-red-500">{{ form.errors.start_time }}</small>
                </div>
                <div>
                    <label for="end_time" class="block font-bold mb-3 required-field">Kết thúc</label>
                    <DatePicker id="end_time" v-model="form.end_time" showTime hourFormat="24" :invalid="form.invalid('end_time')" dateFormat="dd/mm/yy" fluid />
                    <small v-if="form.invalid('end_time')" class="text-red-500">{{ form.errors.end_time }}</small>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-3 mt-6">
                    <div>
                        <label for="qty" class="block font-bold mb-3 required-field">Số lượng</label>
                    </div>
                    <div>
                        <label for="unit" class="block font-bold mb-3 required-field">Đơn vị</label>
                    </div>
                    <div>
                        <label for="delivery_time" class="block font-bold mb-3 required-field">Thời gian giao</label>
                    </div>
                    <div>
                        <label for="delivery_time" class="block font-bold mb-3">Thao tác</label>
                    </div>
                </div>
            <div v-for="(row, index) in form.quantities" :key="index">
                <div class="grid grid-cols-4 gap-3 mb-6">
                    <div>
                        <InputNumber v-model="row.qty" @change="form.validate('qty')" autofocus :invalid="submitted && form.errors[`quantities.${index}.qty`]" fluid />
                        <small v-if="form.errors[`quantities.${index}.qty`]" class="text-red-500">{{ form.errors[`quantities.${index}.qty`] }}</small>
                    </div>
                    <div>
                        <Select v-model="row.unit" @change="form.validate('unit')" :options="units" :invalid="submitted && form.errors[`quantities.${index}.unit`]" filter class="w-full" placeholder="Chọn đơn vị" />
                        <small v-if="form.errors[`quantities.${index}.unit`]" class="text-red-500">{{ form.errors[`quantities.${index}.unit`] }}</small>
                    </div>
                    <div>
                        <InputText id="delivery_time" v-model="row.delivery_time" @change="form.validate('delivery_time')" autofocus :invalid="submitted && form.errors[`quantities.${index}.delivery_time`]" fluid />
                        <small v-if="form.errors[`quantities.${index}.delivery_time`]" class="text-red-500">{{ form.errors[`quantities.${index}.delivery_time`] }}</small>
                    </div>
                    <div>
                        <Button v-if="0 == index" class="col-span-full" severity="info" label="Thêm" @click="addRow"></Button>
                        <Button v-if="index >= 1" class="col-span-full" severity="danger" label="Xóa" @click="removeRow(index)"></Button>
                    </div>
                </div>
            </div>
            <Button class="col-span-full mt-6" label="Lưu" icon="pi pi-check" :disabled="form.hasErrors" @click="updateTender" />
        </Fluid>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useForm } from 'laravel-precognition-vue-inertia';
import { useToast } from 'primevue/usetoast';
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const toast = useToast();
const page = usePage();
const message = computed(() => page.props.auth.flash.message);

const props = defineProps({
    errors: {
        type: Object,
    },
    tender: Object,
    materials: Object,
    quantities: Object,
});

const units = ['Tấn', 'Kg', 'Chiếc'];
const form = useForm('put', '/admin/tenders/1', {
    id: props.tender.id,
    title: props.tender.title,
    material: props.tender.material,
    origin: props.tender.origin,
    packing: props.tender.packing,
    certificate: props.tender.certificate,
    freight_charge: props.tender.freight_charge,
    other_term: props.tender.other_term,
    delivery_condition: props.tender.delivery_condition,
    payment_condition: props.tender.payment_condition,
    start_time: null,//TODO: need to update start_time
    end_time: null,//TODO: need to update start_time
    status: props.tender.status,
    quantities: props.quantities,
});
const submitted = ref(false);


const updateTender = () => {
    submitted.value = true;

    // Update Tender
    form.submit({
        onSuccess: () => {
            form.reset();
            toast.add({severity: 'success', summary: 'Thành công', detail: message, life: 3000});
        },
        onError: () => {
            toast.add({severity: 'error', summary: 'Lỗi', detail: message, life: 3000});
        },
    });
};

const addRow = () => {
    form.quantities.push({ qty: 0, unit: null, delivery_time: null });
};

const removeRow = (index) => {
    form.quantities.splice(index, 1);
}
</script>
