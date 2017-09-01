Способы деплоя приложения

Первый способ:
1) Скачать проект с GitHub
2) Извлечь из архива в папку
3) Установить VirtualBox и Vagrant
4) Открыть консоль и перейти в корень проекта
5) Запустить команду vagrant up (Создаст окружение и домены y2aa-frontend.dev и y2aa-backend.dev)
6) Зарегистрироваться на сайте и подтвердить email (установлена опция useFileTransport)
7) Из консоли войти по ssh на виртуальную машину (vagrant ssh)
8) Перейти в папку /app
9) Выполните ./yii rbac/assign mail@xxx admin, где mail@xxx ваш email, под которым вы зарегистрировались.
10) Выполнить комманду из консоли ./yii test/generate 100 100
11) Проект развернут.

Второй способ:
1) Установить php 7.1, postgress и веб-сервер. 
2) Создайте папку для проекта. Скачайте и распакуйте проект в ее корень
3) Создайте БД в PostgreSQL
----------------------------------
sudo -u postgres psql -c "CREATE USER root WITH PASSWORD 'root'"
service postgresql restart
sudo -u postgres psql -c "CREATE DATABASE yii2advanced"
sudo -u postgres psql -c "CREATE DATABASE yii2advanced_test"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE yii2advanced TO root"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE yii2advanced_test TO root"
service postgresql restart
----------------------------------
4) Поправить конфиги вебсервера, указав пути к бекенду и фронтенду, а также домены. В конфиге фреймворка указать данные для подключения к БД
5) Перейти из консоли в папку с проектом
6) Выполнить yii migrate
7) Зарегистрироваться в системе и подтвердить email ((установлена опция useFileTransport))
8) Выполните ./yii rbac/assign mail@xxx admin, где mail@xxx ваш email, под которым вы зарегистрировались.
9) Выполнить комманду из консоли ./yii test/generate 100 100
10) Проект развернут.