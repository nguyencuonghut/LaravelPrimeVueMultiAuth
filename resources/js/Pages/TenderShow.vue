<template>
    <Head>
        <title>Chi tiết bộ thầu</title>
    </Head>

    <div class="card">
        <Tabs value="0">
            <TabList>
                <Tab value="0">Chi tiết</Tab>
                <Tab value="1">Chào giá</Tab>
                <Tab value="2">Kết quả</Tab>
                <Tab value="3">Nhật ký</Tab>
            </TabList>
            <TabPanels>
                <TabPanel value="0">
                    <span class="font-bold mb-8 block text-xl">{{ tender.title }}</span>
                    <Fluid>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <span class="font-bold mb-2 block">Tên hàng</span>
                                <span class="mb-8 block">{{ tender.material }}</span>
                            </div>
                            <div>
                                <span class="font-bold mb-2 block">Xuất xứ</span>
                                <span class="mb-8 block">{{ tender.origin }}</span>
                            </div>
                            <div>
                                <span class="font-bold mb-2 block">Đóng gói</span>
                                <span class="mb-8 block">{{ tender.packing }}</span>
                            </div>
                        </div>
                    </Fluid>
                    <Fluid>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <span class="font-bold mb-2 block">Giao hàng</span>
                                <span class="mb-8 block">{{ tender.delivery_condition }}</span>
                            </div>
                            <div>
                                <span class="font-bold mb-2 block">Thanh toán</span>
                                <span class="mb-8 block">{{ tender.payment_condition }}</span>
                            </div>
                            <div>
                                <span class="font-bold mb-2 block">Cước vận chuyển</span>
                                <span class="mb-8 block">{{ tender.freight_charge }}</span>
                            </div>
                        </div>
                    </Fluid>
                    <Fluid>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <span class="font-bold mb-2 block">Chứng từ</span>
                                <span class="mb-8 block">{{ tender.certificate }}</span>
                            </div>
                            <div>
                                <span class="font-bold mb-2 block">Điều kiện khác</span>
                                <span class="mb-8 block">{{ tender.other_term }}</span>
                            </div>
                            <div>
                                <span class="font-bold mb-2 block">Trạng thái</span>
                                <Badge :value="tender.status" :severity="getStatusSeverity(tender.status)"></Badge>
                            </div>
                        </div>
                    </Fluid>
                    <hr>
                    <span class="font-bold mb-2 mt-6 block">Chất lượng</span>
                    <span class="mb-8 block" style="white-space: pre-line;">{{ tender.quality }}</span>
                    <hr>
                    <Fluid>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <span class="font-bold mb-2 block">Số lượng</span>
                            </div>
                            <div>
                                <span class="font-bold mb-2 block">Đơn vị</span>
                            </div>
                            <div>
                                <span class="font-bold mb-2 block">Thời gian giao</span>
                            </div>
                        </div>
                        <div v-for="(row, index) in quantities" :key="index">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <span class="mb-8 block">{{ row.qty }}</span>
                                </div>
                                <div>
                                    <span class="mb-8 block">{{ row.unit }}</span>
                                </div>
                                <div>
                                    <span class="mb-8 block">{{ row.delivery_time }}</span>
                                </div>
                            </div>
                        </div>
                    </Fluid>
                </TabPanel>
                <TabPanel value="1">
                    <p class="m-0">
                        Chào giá
                    </p>
                </TabPanel>
                <TabPanel value="2">
                    <p class="m-0">
                        Kết quả
                    </p>
                </TabPanel>
                <TabPanel value="3">
                    <p class="m-0">
                        Nhật ký
                    </p>
                </TabPanel>
            </TabPanels>
        </Tabs>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
const props = defineProps({
    errors: {
        type: Object,
    },
    tender: Object,
    quantities: Object,
});

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
