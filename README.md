# API Laravel com Mysql e Docker
Amostra de códido de desenvolvimento de API Laravel

Esta aplicação é uma amostra de código de uma API desenvolvida com Larave, Mysql e Docker.

- Apoś clonar o repositório, e tendo certeza de ter instalado na sua máquina o docker,docker-compose, composer e php, execute o comando abaixo:
docke-compose up -d --build

-Apoś baixar todas as imagens execute os comandos abaixo para entrar no container da app e instalar os arquivos necessário do laravel:
docker exec -it cormec-app bash
cd laravel
composer install
exit;

- Abra o browzer e use o link http://locahost::8080/
Visuzlizando o tela inicial do laravel significa qee a aplicação esta devida instalada e pronta para usar.
Como se trata de uma api, você deve realizer os testes com Postman oualguma outra ferramento similar.

