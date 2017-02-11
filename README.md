Deployment from Git
============================

Данный скрипт предназначен для удобного развертывания проектов из git репозиториев на сервере.
На данный момент поддерживаются сервисы GitHub и BitBucket.

**Список возможностей:**
- Подключение к системе сервисов GitHub и BitBucket
- Регистрация в системе репозиториев из выбранного сервиса
- Детальная статистика действий в системе
- Управление пользователями системы
- Резервное копирование
- Cron

Минимальные требования
----------------------

Веб сервер с поддержкой PHP 5.4 и выше

Установка скрипта
-------------------

**Обычная установка:**
- Скачать архив по [ссылке](https://goo.gl/1evhWG)
- В файле `config/db.php` прописать данные для доступа к бд
- В файле `config/web.php` прописать рандомную строку для поля `cookieValidationKey` *(что на 22 строке)*
- Перейти на свой сайт и следовать инструкциям

**Продвинутая установка:**
- Скачать или склонировать данный репозиторий
- В файле `config/db.php` прописать данные для доступа к бд
- В файле `config/web.php` прописать рандомную строку для поля `cookieValidationKey` *(что на 22 строке)*
- Выполнить комманду `composer install` или `composer install --no-dev` для установки на dev или prod сервере соответственно
- Запустить комманду `php yii migrate` для импотрта схемы бд
- Перейти на свой сайт и следовать инструкциям


Инструкция
------------

Для того чтобы добавить сервис в систему, нужно получить токен, после чего указать логин и токен на странице добавления.

#### Получение токена на GitHub
- Отркыть раздел [Personal access tokens](https://github.com/settings/tokens) что в настроках GitHub
- Указать любое имя и выставить галочки на против полей: `repo:status, repo_deployment и public_repo`
- Полеченный токен ввести на своем сайте при добавление нового сервиса

#### Полечение токена на BitBucket
- Открыть раздел **App passwords** что находится в меню **BitBucket settings**
- Создать новый пароль указав любое название и дав ему права доступа **Repositories: Read**
- Полеченный токен ввести на своем сайте при добавление нового сервиса

После того как вы добавили сервис вы можете зарегистировать в системе репизиторий. Все что для этого нужно, это перейти на страницу добавленя в систему репозитория и указать следующие данные:
- Выбрать сервис
- Выбрать репозиторий
- Выбрать папку на вашем сервере в которую будут извлекатся файлы из вашего репозитория
