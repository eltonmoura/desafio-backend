# Desafio Backend

## Instalação (Usando Docker)

```
git clone https://github.com/eltonmoura/desafio-backend.git
cd desafio-backend
cp .env.example .env
```

Build do projeto com [docker-compose](https://docs.docker.com/compose/install/)
```
docker-compose up --build -d
docker-compose exec web composer install
docker-compose exec web php artisan migrate:fresh --seed
```

A API estará disponível na porta 8080. Exemplo:
```
http://localhost:8080/users
```
Todos recursos disponíveis da API podem ser encontrados no routes/web.php.

Mais exemplos de rotas na collection do [Postman] (https://www.getpostman.com)
Importe de [postman-collection.json](./docs/postman-collection.json)
