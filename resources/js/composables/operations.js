import { ref } from 'vue';
import axios from 'axios';

export default function useOperations() {
    const operations = ref({});
    const is_user_operations = ref({});
    const isLoading = ref(false)

    const getOperations = async (
        page = 1,
        search_description = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        try {
            isLoading.value = true;
            const response = await axios.get('/api/operations', {
                params: {
                    page: page,
                    search_description: search_description,
                    order_direction: order_direction,
                    order_column: order_column
                }
            });
            operations.value = response.data.operations;
            is_user_operations.value = response.data.is_user_operations;
        } catch (error) {
            console.error('Error fetching operations:', error);
        } finally {
            isLoading.value = false;
        }
    };


    return {
        operations,
        is_user_operations,
        getOperations
    };
}
