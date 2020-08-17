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

Mais exemplos de rotas na collection do [Postman](https://www.getpostman.com)
Importe de [postman-collection.json](https://github.com/eltonmoura/desafio-backend/blob/master/docs/postman_collection.json)

## Executando Testes Unitários
```
docker-compose exec web ./vendor/bin/phpunit
```
## ER
![alt text](https://github.com/eltonmoura/desafio-backend/blob/master/docs/er.png?raw=true)

## Considerações
Essa solução procurou atender os requisitos mínimos solicitados. Para isso foi usada uma arquitetura onde em uma única requisição do usuário ocorrem todas às validações, internas e externas.
Levando em conta que nas validações externas podem ocorrer lentidão ou instabilidade, como proposta de melhoria, sugiro modificar esse fluxo, quebrando em duas partes.
A primeira requisição de transferência faria apenas as validações internas e gravaria no banco de dados ou em um serviço de fila, então retorna para o usuário sem a confirmação de processamento. Em segundo plano, um “job” estaria consumindo essa fila, processando as validações externas e enviando a confirmação de processamento para os usuários.
Assim garantimos uma maior performance e escalabilidade.
