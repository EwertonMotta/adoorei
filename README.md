## Desafio Back-End Adoorei

Para iniciar o projeto, é necessário ter o Docker instalado.

Basta executar na pasta do projeto o comando ```./vendor/bin/sail up -d``` que será criado e iniciado os containers.

Para executar a migração e o seed dos ```Produtos``` execute o comando ```./vendor/bin/sail artisan migrate --seed```.

Existe uns scripts interessantes no arquivo ```composer.json```

```json
"scripts": {
    [...],
    "format": "./vendor/bin/pint",
    "analyse": "./vendor/bin/phpstan analyse",
    "type": "./vendor/bin/pest --type-coverage",
    "test": "./vendor/bin/pest --parallel",
    "ci": [
        "@format",
        "@analyse",
        "@type",
        "@test"
    ]
}
```
- ```./vendor/bin/sail composer format``` irá usar o Laravel Pint para formatar o código no padrão Laravel;
- ```./vendor/bin/sail composer analyse``` irá usar o phpstan optimizado para o Laravel (larastan) para verificar erros no código
- ```./vendor/bin/sail composer type``` irá usar o plugin de type coverage do PestPHP para mensurar a porcentagem do código tipado
- ```./vendor/bin/sail composer test``` irá usar o PestPHP para executar os testes da aplicação
- ```./vendor/bin/sail composer ci``` irá executar todos os comando acima
