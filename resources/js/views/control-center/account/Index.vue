<template>
    <div class="row justify-content-center my-2">
        <div class="col-md-12">
            <div class="card border-0">
                <div class="card-header bg-transparent">
                    <h5 class="float-start"><span class="text-muted">Account:</span> <span class="font-weight-bold text-decoration-underline">{{ balance.email }}</span></h5>
                    <h5 class="float-end"><span class="text-muted">Balance:</span> <span class="font-weight-bold text-decoration-underline">{{ balance.amount }}</span></h5>
                </div>
                <div class="card-body shadow-sm">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Created at</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Amount</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Type</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Description</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="operation in operations" :key="operation.id">
                                <td class="px-6 py-4 text-sm" width="20">
                                    {{ operation.id }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ operation.created_at }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ operation.amount }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ operation.operation_type }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ operation.status }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div v-html="operation.description.slice(0, 100) + '...'"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {ref, onMounted, watch} from "vue";
    import useAccount from "@/composables/account";
    import {useAbility} from '@casl/vue'

    const {operations, balance, getAccountInfo, isLoading, startAutoRefresh} = useAccount()
    const {can} = useAbility();
    onMounted(() => {
        startAutoRefresh(); // Запускаем автообновление при загрузке компонента
        getAccountInfo(); // Получаем данные в начальный момент времени
    });
</script>
