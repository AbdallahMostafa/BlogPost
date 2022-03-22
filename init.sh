# ! bring down any service instance if it exists

docker-compose down

docker-compose up -d --build

docker exec -i symfony composer install
