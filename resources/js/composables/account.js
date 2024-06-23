import { ref } from 'vue';
import axios from 'axios';

export default function useAccount() {
    const operations = ref({});
    const balance = ref({});
    const isLoading = ref(false);

    const getAccountInfo = async (
    ) => {
        try {
            isLoading.value = true;
            const response = await axios.get('/api/account', {
            });
            operations.value = response.data.operations;
            balance.value = response.data.balance;
        } catch (error) {
            console.error('Error fetching account_info:', error);
        } finally {
            isLoading.value = false;
        }
    };

    const startAutoRefresh = () => {
        setInterval(() => {
            getAccountInfo(); // Вызываем функцию для получения данных с текущими параметрами
        }, 5000); // Обновление каждые 5 секунд
    };

    return {
        operations,
        balance,
        getAccountInfo,
        isLoading,
        startAutoRefresh // Возвращаем функцию для запуска автообновления
    };
}
