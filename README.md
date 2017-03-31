# Laravel Elasticache

Este pacote tem como finalidade adicionar um novo driver chamado
`elasticache` para você usar como driver de cache e de sessão  no 
Laravel. O pacate usa o `Memcached` como servidor de armazenamento e
é compatível com a extensão do Memcached desenvolvida pela própria
Amazon. Esta extensão tem como diferencial, a descoberta automática
dos nós do seu cluster sem a necessidade de adicionar os nós 
manualmente. 

### Instalação

Para instalar o pacote, basta usar o composer

```
composer require db1-fpp/laravel-elasticache
```

Depois basta adicionar o provider nas configurações da aplicação
localizado no arquivo `config/app.php`

```php
Db1Fpp\Providers\ElastiCacheServiceProvider::class
```

Ou com Lumen

```php
$app->register(Db1Fpp\Providers\ElastiCacheLumenServiceProvider::class);
```

### Configuração

O primeiro passo é adicionar a lista de servidores do Elasticache
que você irá usar na sua aplicação. Isto deve ser feito no arquivo
de configuração de cache do laravel.

```php
// config/cache.php

[
    //... others cache config
    
    'elasticache' => [
        'default' => [
            'mode'  => env('MEMCACHED_CACHE_MODE', 'dynamic'),
            'nodes' => [
                [
                    'host'   => env('MEMCACHED_CACHE_HOST', '127.0.0.1'),
                    'port'   => env('MEMCACHED_CACHE_PORT', 11211),
                    'weight' => env('MEMCACHED_CACHE_WEIGHT', 100)
                ]
            ]
        ],
        'sessions' => [
            'mode'  => env('MEMCACHED_SESSION_MODE', 'dynamic'),
            'nodes' => [
                [
                    'host'   => env('MEMCACHED_SESSION_HOST', '127.0.0.1'),
                    'port'   => env('MEMCACHED_SESSION_PORT', 11211),
                    'weight' => env('MEMCACHED_SESSION_WEIGHT', 100)
                ]
            ]
        ]
    ]
]
```

A chave connections aceita um array onde você pode definir várias conexões que poderão
ser usadas pelas sua aplicação, veremos mais a frente como.

**mode**: indica qual modo será usado para descoberta dos nós do seu cluster. Os valores
possíveis são `static` e `dynamic`

**OBS: PARA USAR O MODO `dynamic` OBRIGATÓRIAMENTE VOCÊ PRECISA INSTALAR A EXTENSÃO DO
MEMCACHED FORNECIDA PELA AMAZON**

**nodes**: são os nós que fazem parte do seu cluster. Caso você esteja usando o modo `dynamic`
aqui deverá ser adicionado o endpoit de configuração fornecido pela Amazon

### Utilização

Após adicionar as configurações do cluster, você estará apto para utilizar o driver `elasticache`
para cache e também para sessão.

```php
/*
 * config/cache.php
 *
 * Other stores before here...
 */
 'elasticache' => [
    'driver' => 'elasticache'
    'connection' => 'default'
 ]
```

Você também pode usar como driver de sessão inclusive com a possibilidade de usar um servidor
diferente do que é utilizado para armazenamento do cache.

```php
/*
 * config/session.php
 */
 
 'driver' => 'elasticache'
 
 // ...other sessions configs here
 
 'elasticache_connection' => 'sessions'
```

### License

[MIT](http://opensource.org/licenses/MIT)