<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;

class AddUserCommand extends Command
{
    protected $signature = 'user:add {name} {email} {password}';
    protected $description = 'Add a new user';

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Валидация данных
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Проверка валидации
        if ($validator->fails()) {
            // Получаем первую ошибку по каждому полю
            $errors = $validator->errors();

            if ($errors->has('email')) {
                // Если ошибка связана с уникальностью email
                $this->error('Email already exists. Please enter a different email.');
            } elseif ($errors->has('password')) {
                // Если ошибка связана с неверным форматом пароля
                $this->error('Invalid password format or too short.');
            } else {
                // Если другие ошибки валидации
                $this->error('Invalid data provided.');
            }

            return;
        }

        // Создание пользователя через репозиторий
        $user = $this->userRepository->createUser([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role_id' => User::ROLE_USER, // Предположим, что у пользователя есть роль по умолчанию
        ]);

        if ($user) {
            $this->info("User {$name} with email {$email} has been added successfully.");
        } else {
            $this->error("Failed to add user {$name} with email {$email}.");
        }
    }
}
