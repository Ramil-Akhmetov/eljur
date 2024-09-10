# Электронный журнал для учета успеваемости студентов

## Установка

Установите зависимости

```bash
composer install
```

Создайте .env из шаблона

```bash
cp .env.example .env
```

Введите необходимые данные для подключения к MySQL

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eljur
DB_USERNAME=root
DB_PASSWORD=root
```

Сгенерируйте ключ шифрования

```bash
php artisan key:generate
```

Запустите миграций и сидеры

```bash
php artisan migrate --seed
```

Если БД нужно пересоздать и прогнать сидеры

```bash
php artisan migrate:fresh --seed
```

Изменить переменную окружения APP_URL, чтобы она ввела на приложение

```env
APP_URL=http://localhost:8000
```

На некоторых системах через консоль в приложении появляется ошибка 419 page expired, на хостинге такую ошибку не замечал, поэтому можно попробывать открыть проект через XAMP или OpenServer, не забыв поменять переменную окружения APP_URL

Запуск локального сервера через консоль

```bash
php artisan serve
```

### Учетная запись администратора

email: <admin@email.com>

пароль: password

### Для входа в учетные записи преподавателей и студентов

Зайдите в учетную запись администратора и найдите вкладку где в

---

Для всех учетных записей по умолчанию пароль password