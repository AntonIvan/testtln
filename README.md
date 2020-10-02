Запуск контейнеров

в папке /docker/backend/ запускаем сборку
docker-compose build

далее
docker-compose up

заходим в контейнер backend командой
docker exec -it backend_testtln_backend_1 /bin/bash

и выполняем миграцию
php artisan migrate

для проверки использовал ИНН 500100732259
