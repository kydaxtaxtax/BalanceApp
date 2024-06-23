<template>
    <div class="row justify-content-center my-2">
        <div class="col-md-12">
            <div class="card border-0">
                <div class="card-header bg-transparent">
                    <h5 class="float-start">Operations</h5>
                </div>
                <div class="card-body shadow-sm">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 text-start"></th>
                                <th class="px-6 py-3 text-start"></th>
                                <th class="px-6 py-3 text-start" v-if="!is_user_operations"></th>
                                <th class="px-6 py-3 text-start"></th>
                                <th class="px-6 py-3 text-start"></th>
                                <th class="px-6 py-3 text-start"></th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <input v-model="search_description" type="text"
                                           class="inline-block mt-1 form-control"
                                           placeholder="Filter by Description">
                                </th>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</span>
                                </th>

                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <div class="flex flex-row items-center justify-between cursor-pointer"
                                         @click="updateOrdering('created_at')">
                                        <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                             :class="{ 'font-bold text-blue-600': orderColumn === 'created_at' }">
                                            Created at
                                        </div>
                                        <div class="select-none">
                                            <span :class="{
                                              'text-blue-600': orderDirection === 'asc' && orderColumn === 'created_at',
                                              'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'created_at',
                                            }"class="arrow-icon">&uarr;</span>
                                                        <span :class="{
                                              'text-blue-600': orderDirection === 'desc' && orderColumn === 'created_at',
                                              'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'created_at',
                                            }"class="arrow-icon">&darr;</span>
                                        </div>
                                    </div>
                                </th>

                                <th class="px-6 py-3 bg-gray-50 text-left" v-if="!is_user_operations">
                                    <div class="flex flex-row items-center justify-between cursor-pointer"
                                         @click="updateOrdering('email')">
                                        <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                             :class="{ 'font-bold text-blue-600': orderColumn === 'user_id' }">
                                            Email
                                        </div>
                                        <div class="select-none">
                                                <span :class=" {
                                                  'text-blue-600': orderDirection === 'asc' && orderColumn === 'user_id',
                                                  'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'user_id',
                                                }"class="arrow-icon">&uarr;</span>
                                            <span :class="{
                                                  'text-blue-600': orderDirection === 'desc' && orderColumn === 'user_id',
                                                  'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'user_id',
                                                }"class="arrow-icon">&darr;</span>
                                        </div>
                                    </div>
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
                            <tr v-for="operation in operations.data" :key="operation.id">
                                <td class="px-6 py-4 text-sm" width="20">
                                    {{ operation.id }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ operation.created_at }}
                                </td>
                                <td class="px-6 py-4 text-sm" v-if="!is_user_operations">
                                    {{ operation.email }}
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
                <div class="card-footer">
                    <Pagination :data="operations" :limit="3"
                                @pagination-change-page="page => getOperations(page, search_description, orderColumn, orderDirection)"
                                class="mt-4"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {ref, onMounted, watch} from "vue";
    import useOperations from "@/composables/operations";
    import {useAbility} from '@casl/vue'

    const search_description = ref('')
    const orderColumn = ref('created_at')
    const orderDirection = ref('desc')
    const {operations, is_user_operations, getOperations} = useOperations()
    const {can} = useAbility();
    onMounted(() => {
        getOperations()
    })
    const updateOrdering = (column) => {
        orderColumn.value = column;
        orderDirection.value = (orderDirection.value === 'asc') ? 'desc' : 'asc';
        getOperations(
            1,
            search_description.value,
            orderColumn.value,
            orderDirection.value
        );
    }

    watch(search_description, _.debounce((current, previous) => {
        getOperations(
            1,
            search_description.value,
            orderColumn.value,
            orderDirection.value,
            current
        )
    }, 200))

</script>


