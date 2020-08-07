# ApiCore

[![License](https://img.shields.io/badge/license-MIT-green)](https://github.com/GustavoSantosBr/)
[![Minimum PHP Version](https://img.shields.io/badge/php-%5E7.3.6-blue)](https://php.net/)

 ApiCore fornece respostas de requisições de maneira simples e personalizada, exceções e validadores.
 
 Os recursos foram criados levando em conta sua utilização em frameworks como [Zend Expressive](https://github.com/zendframework/zend-expressive) e [Mezzio](https://github.com/mezzio/mezzio).

* [Instalação](#Instalação)
* [Configuração](#Configuração)
* [Implementação](#Implementação)
  - [Hateoas](#Hateoas)
  - [Validadores](#Validadores)
  - [Exceções](#Exceções)
  - [Respostas](#Respostas)
 * [Observações ](#Observações )

## Instalação

Para realizar a instalação, utilize o [Composer](https://getcomposer.org/).

Execute o comando:
```bash
composer require gustavosantos/api-core
```
## Configuração

Dentro da pasta **autoload**, defina um arquivo de configuraçao com a seguinte estrutura:

```php
<?php

declare(strict_types=1);

return [
    "api_core" => [
        "default_code_error" => -1, # Define o código de erro interno padrão.
        "default_message_error" => "Ocorreu um erro inesperado na API!", # Define uma mensagem padrão para erros não tratados
        "default_base_uri" => "http://localhost:8081/", # Define a uri padrão para as respostas com Hateoas
    ]
];
```
Construa uma Middleware para o carregamento do **ContainerInterface**, e a implemente na **pipeline**.

```php
<?php

declare(strict_types=1);

namespace SeuNamespace;

use ApiCore\LoadConfigData;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoadConfigDataMiddleware implements MiddlewareInterface
{
    public function __construct(ContainerInterface $container)
    {
        LoadConfigData::setContainer($container);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}
```

```php
# Adicione após RouteMiddleware

 $app->pipe(LoadConfigDataMiddleware::class);
```

## Implementação

### Hateoas

Para configurar **Hateoas** nas respostas de sucesso da API, basta utilizar as anotações
do **HateoasCore** em sua entidade ou DTO. 

   - **RestCore**: 
        
     |  Propriedade  |    Obrigatório    |  Descrição                                                                                                                                                                                                                                                                                 | 
     |     :---:     |       :---:       |  :---:                                                                                                                                                                                                                                                                                     |
     |  `params`     |        Sim        |  Recebe um array de **ParamsCor** _&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;__&nbsp;_ |
         
   - **ParamsCore**:
   
     |  Propriedade  |    Obrigatório    |  Descrição                                          |
     |     :---:     |       :---:       |  :---:                                              |
     |  `title`      |        Não        |  Um texto qualquer                                  |
     |  `rel`        |        Não        |  Indica o relacionamento                            |
     |  `method`     |        Sim        |  Método HTTP                                        |
     |  `href`       |        Sim        |  Uma URL completa que deve definir um único recurso |
     
   - **ValueCore**:
 
     |  Propriedade  |    Obrigatório    |  Descrição                                                      |
     |     :---:     |       :---:       |  :---:                                                          |
     |  `key`        |        Sim        |  Utilizado para o mapeamento interno dos parâmetros _&nbsp;_    |
        
Obs: O valor/nome passado como parâmetro no `href` deve ser igual ao `key`. O `key` deve corresponder a um atributo da classe.
 
Exemplo:

```php
<?php

declare(strict_types=1);

namespace SeuNamespace;

use ApiCore\Hateoas\Annotation\RestCore;
use ApiCore\Hateoas\Annotation\ParamsCore;
use ApiCore\Hateoas\Annotation\ValueCore;
use JMS\Serializer\Annotation\Type;

/**
 * @RestCore(params={
 *     @ParamsCore(title="Inserir usuário", rel="post_user", method="POST", href="v1/usuarios"),
 *     @ParamsCore(title="Consultar usuário por user_id", rel="get_user_by_user_id", method="GET", href="v1/usuarios/user_id", params={@ValueCore(key="user_id")})
 * })
 */
class User
{
    /**
     * @var int
     * @Type("int")
     */
    private $user_id;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }
}
```
### Validadores

Para utilizar o **ValidatorCore**, você pode utiliza-lo diretamente, ou ainda, estendê-lo.
Os objetos validados pelo **ValidatorCore**, devem implementar **ObjectCoreInterface**.

As restrições são adicionadas usando as anotações do **Symfony** nas propriedades da sua classe.

Exemplos:

```php
<?php

declare(strict_types=1);

namespace SeuNamespace;

use ApiCore\Validation\ObjectCoreInterface;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints\NotBlank;

class User implements ObjectCoreInterface
{
    /**
     * @var int
     * @Type("int")
     * @NotBlank(message="O campo user_id é obrigatório!")
     */
    private $user_id;
}
```

```php
<?php

declare(strict_types=1);

namespace SeuNamespace;

use ApiCore\Validation\ValidatorCore;

$user = new User();
(new ValidatorCore())->validateCore($user);
```

Resultado:
```json
{
    "status_code": 400,
    "error": [
        {
            "internal_message_error": "O campo user_id é obrigatório!"
        }
    ]
}
```

### Exceções

Para utilizar o **ExceptionCore**, você pode utiliza-lo diretamente, ou ainda, estendê-lo.

 - **Error**: 
     - `status_code`: O código de status HTTP aplicável a esse problema, expresso como um valor de sequência;
     - `code`: Um código de erro específico do aplicativo, expresso como um valor de sequência
     - `message_error`: Um resumo/texto "amigável" sobre o erro;
     - `internal_message_error`: Um resumo/texto sobre erro voltado para o desenvolvedor, erro de integração e etc;
     - `trace_error`: Exceções sem tratamento ou pilha de rastreio do erro;

Obs: `repeatMessage` não é exibido/serializado, apenas define se o valor de `message_error` deve ser repetido em `internal_message_error`.

```php
use ApiCore\Exception\Config;
use ApiCore\Exception\ExceptionCore;
use Http\StatusHttp;

throw new ExceptionCore((new Config())
                ->setStatusCode(StatusHttp::BAD_REQUEST)
                ->setMessageError("Ocorreu um erro ao processar sua solicitação, entre em contato com o suporte!")
                ->setInternalMessageError("O campo user_id é obrigatório!"));
```

Resultado:
```json
{
    "status_code": 400,
    "error": [
        {
            "message_error": "Ocorreu um erro ao processar sua solicitação, entre em contato com o suporte!",
            "internal_message_error": "O campo user_id é obrigatório!"
        }
    ]
}
```

### Respostas

Para utilizar o **JsonResponseCore**, você pode utiliza-lo diretamente ou construí-lo com **JsonResponseCoreBuilder**.

```php
<?php

declare(strict_types=1);

namespace SeuNamespace;

use ApiCore\Response\JsonResponseCore;
use Http\StatusHttp;
use User;

$user = new User();
$user->setUserId(1);
$user->setName("Gustavo");

return new JsonResponseCore($user, StatusHttp::CREATED);
```

ou 

```php
<?php

declare(strict_types=1);

namespace SeuNamespace;

use ApiCore\Response\JsonResponseCoreBuilder;
use Http\StatusHttp;
use User;

$user = new User();
$user->setUserId(1);
$user->setName("Gustavo");

return (new JsonResponseCoreBuilder())
                ->setData($user)
                ->setStatusCode(StatusHttp::CREATED)
                ->build();
```

Resultado:
```json
{
    "status_code": 201,
    "data": {
        "user_id": 1,
        "name": "Gustavo"
    },
    "links": [
        {
            "title": "Inserir usuário",
            "rel": "post_user",
            "method": "POST",
            "href": "http://localhost:8081/v1/usuarios"
        },
        {
            "title": "Consultar usuário por user_id",
            "rel": "get_user_by_user_id",
            "method": "GET",
            "href": "http://localhost:8081/v1/usuarios/1"
        }
    ]
}
```

### Observações
 ##### Erros:
 - **Not be accessed before initialization**:
 
      Como o PHP 7.4 introduz dicas de tipo para propriedades, é particularmente importante fornecer valores válidos para 
      todas as propriedades, para que todas as propriedades tenham valores que correspondam aos tipos declarados.
      Uma variável que nunca foi atribuída não tem um valor **nulo**, mas está em um estado **indefinido**, que nunca 
      corresponderá a nenhum tipo declarado.
      
      `undefined !== null`
      
     Exemplo: 
     
   ```php
   private $user_id = null; 
   ```
   ou 
   
   ```php
   private ?int $user_id = null; 
   ```
         
