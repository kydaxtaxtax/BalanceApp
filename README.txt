Приветствую!

Было очень интересно выполнить тестовое задание. Я немного увлекся и сделал даже то, что не требовалось)

Как поднять:
1. Собираем приложение
    composer install

2. создаем .env и настраиваем в нем подключеие к БД, а также меняем очередь на QUEUE_CONNECTION=database

3. Добавляем ключ
    php artisan key:generate

4. Делаем миграции
    php artisan migtare

5. Добавляем тестовых пользователей с нужными правами,
а также наполняем таблицы бананса и опереций тестовыми данными.
    php artisan db:seed

    у всех пароль 12345678

6. Собираем фронт
    npm run build




В тестовых данных есть 2 роли (Админ и пользователь)

    - У пользователя есть страница аккаунта и страница банаса выполненная по ТЗ.
        Для пользователя мы так же не показываем столбец email в операциях.

    - У Админа есть управление Пользователями, ролями и разрешениями,
    а также просмотр операций всех пользователей



Чтобы запустить операцию через artisan:
    - включаем очередь для операций
        php artisan queue:work --queue=operations
    - запусаем выполнение операции (id пользователя, сумма, тип операции (credit или debit) и описание по желанию)
        php artisan add:operation 2 100 credit "This is a comment"

Чтобы добавить пользователя через команду:
    php artisan user:add John john@example.com secret_password
