<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DateTime;
use Illuminate\Support\Facades\DB;

class CreateBalancesAndOperations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('balances')->insert([
            ['user_id' => 2, 'amount' => 1000.00, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'amount' => 2000.00, 'created_at' => now(), 'updated_at' => now()],
        ]);


// Генератор для вставки строк в таблицу 'operations'
        $operations = [];

// Различные описания операций
        $descriptions = [
            'Initial Deposit',
            'Purchase',
            'Payment',
            'Withdrawal',
            'Refund',
            'Transfer',
            'Fee',
            'Salary',
            'Bonus',
            'Dividend',
        ];

        $amounts = [1000.11, 100.15, 50.21, 2000.00, 200.06, 500.66, 300.53, 1500.92, 75.87, 800.15, 794.15, 10.77];
        $operation_types = ['credit', 'debit'];
        $statuses = ['completed'];
        $balance_ids = [1, 2];

        // Текущая дата и время
        $startDateTime = new DateTime('2023-01-01');
        $endDateTime = new DateTime('2024-06-23');

        // Создание массива данных для вставки
        $operations = [];
        for ($i = 0; $i < 150; $i++) {
            // Генерируем случайную дату в пределах заданного временного диапазона
            $randomTimestamp = mt_rand($startDateTime->getTimestamp(), $endDateTime->getTimestamp());

            // Создаем объект DateTime из случайного временного штампа
            $randomDateTime = new DateTime();
            $randomDateTime->setTimestamp($randomTimestamp);

            // Генерируем случайные индексы для выбора данных из массивов
            $randomDescriptionIndex = array_rand($descriptions);
            $randomAmountIndex = array_rand($amounts);
            $randomOperationTypeIndex = array_rand($operation_types);
            $randomStatusIndex = array_rand($statuses);
            $randomBalanceIdIndex = array_rand($balance_ids);

            $operations[] = [
                'description' => $descriptions[$randomDescriptionIndex],
                'amount' => $amounts[$randomAmountIndex],
                'operation_type' => $operation_types[$randomOperationTypeIndex],
                'status' => $statuses[$randomStatusIndex],
                'balance_id' => $balance_ids[$randomBalanceIdIndex],
                'created_at' => $randomDateTime->format('Y-m-d H:i:s'),
                'updated_at' => $randomDateTime->format('Y-m-d H:i:s'),
            ];
        }

        // Вставка данных в таблицу 'operations'
        DB::table('operations')->insert($operations);

    }
}
