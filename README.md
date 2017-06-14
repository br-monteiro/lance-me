![Logotipo](https://raw.githubusercontent.com/br-monteiro/lance-me/master/projeto/Logotipo/logotipo_md.png)

##### Uma simples API para um encurtador de URLs. Desenvolvido em PHP, MVC e Café!

### Dependências
* PHP5 5.6+
* Apache Server 2.4 +
* MySQL 5.5 +

### Instalação
Para facilitar a instalação das dependências, execute o script __instal.sh__ da raiz do procjeto como usuário root.
Neste arquivo, econtram-se os comandos necessários para instalação dos programas e suas dependências.
É importante lembrar que quando o SGBD MySQL estiver sendo instalado, será necessário fornecer uma senha para o usuário root do MySQL.
__Guarde esta senha__ em um local seguro, pois precisaremos dela posteriormente.

### Configurações
__Servidor HTTP__: Após a instalação do servidor, é preciso apontar o *DocumentRoot* do Apache para a pasta __public__ dentro do projeto.
Para realizar esta alteração, é preciso editar os seguintes arquivos:
```
# nano /etc/apache2/apache2.conf
```
Alterar a linha:
```
   <Directory /var/www/>
```
Para fica assim:
```
   <Directory /diretorio/da/aplicacao/public/>
         Options Indexes FollowSymLinks
         AllowOverride All
         Require all granted
   </Directory>
```
Arquivo 2:
```
# nano /etc/apache2/sites-available/000-default.conf
```
ou
```
# nano /etc/apache2/sites-available/default.conf
```
Alterar a linha:
```
    DocumentRoot /var/www
```
para
```
    DocumentRoot /diretorio/da/aplicacao/public/
```

__Habilitando Modo rewrite__: Esta configuração é necessária para o correto funcionamento das rotas
```
# a2enmod rewrite
```
Após as alterações, reinicie o Apache Server:
```
# service apache2 restart
```

__Criação do Banco de Dados__: Para que a aplicação seja usada, é necessário importar o Banco de Dados; isso pode ser feito importando arquivo SQL (*dump.sql*) encontrado na raiz da aplicação. Executando a importação:
1. Navegue até a raiz da aplicação
2. Execute o comando (Será necessário fornecer a senha do usuário root do MySQL):
```
$ mysql -uroot -p < dump.sql 
```

__Informando usuário e senha do SGBD__: Estamos quase lá! Agora, para que o sistema reconheça o SGBD e consiga se conectar ao mesmo é necessário fornecer as credenciais de acesso. Edite o arquivo *App/Config/ConfigDatabase.php* e altere conforme abaixo:
```php
    public $db = [
        'sgbd' => 'mysql',
        'server' => 'localhost', // <- informe o servidor de Banco de Dados
        'dbname' => 'lanceme',
        'username' => 'root',
        'password' => 'SENHA_DEFINIDA_NA_ISTALAÇÃO_DO_MYSQL', // <- Altere aqui!
        'options' => [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"],
    ];
```

### Arquivos e Diretórios
Árvore de diretórios e arquivo da Aplicação
```
[raiz]
    |_App
    |    |_Config
    |    |    |_ConfigDatabase.php
    |    |_Controllers
    |    |    |_EnderecosController.php
    |    |    |_UsuariosController.php
    |    |_Models
    |    |    |_EnederecosModel.php
    |    |    |_UsuariosModel.php
    |    |_Route
    |         |_Route.php
    |_public
    |    |_index.php
    |_src
         |_Bootstrap
         |    |_InitApp.php
         |_Controller
         |    |_AbstraxtController.php
         |_Database
         |    |_SGBD
         |    |    |_Mysql.php
         |    |    |_SGBDAbstract.php
         |    |_ModelAbstract.php
         |    |_SGBDConnection.php
         |_Http
         |    |_Header.php
         |_Interfaces
         |    |_ControllerInterface.php
         |_RouteSystem
              |_RouteMap.php
              |_Router.php
              |_RouterSystemConfig.php
```
### Funcionamento
Basicamente, os acessos são recebidos pelo arquivo *public/index.php* que por sua vez inicia a aplicação. Ao instanciar a Classe *\LanceMeCore\Bootstrap\InitApp()* o Roteador do sistema entra em ação para validar a requisição e rota acessada, caso tudo ocorra como o esperado, são guardados os parâmetros enviados na URI; contudo, se a Rota requisitada não estiver registrada na Classe */App/Route/Route.php*, o roteador muda o Status da requisição para __404 Not Found__ e aborta a execução do script.

Após a confirmação da rota, o Controller e a Action definidas para a mesma são validados, caso um deles não esteja em conformidade uma __\Exception(...)__ é lançada e o script abortado. Se nenhuma exceção foi lançada o método __run()__ será executado, o que instaciara o Controller da rota e a execução da action;

__Rotas__: As rotas são registradas na Classe */App/Route/Route.php* dentro dométodo _registra()_  com o seguinte padrão:
```php
        $this->routeMap->rotaGet([ // tipo do Método HTTP da Rota (Apenas GET, POST e DELETE estão implementados)
            '/users/{userId}/stats', // definição da rota. Esta rota possui parâmetros em sua composição.
            // Controller e Action definidos para a rota. Caso a action seja omitida, então executará index() por padrão.
            'EnderecosController@statsPorUsuario', 
                ['userId' => '/\w+/'] // Expressão regular definida para validação do parâmetro
        ]);
```

__Controllers__: Os Controllers têm a simples função de chamar os métodos responsáveis por determinada ação no sistema. Para a criação de um novo Controller, são necessárias as seguites observações:
1. Estar definido detro do *namespace App\Controollers;*
2. Extender a Classe *\LanceMeCore\Controller\AbstractController;*
3. Implementar a Interface *\LanceMeCore\Interfaces\ControllerInterface*;

__Models__: Neste sistema, os Models são Classes que manipulam o CRUD e validam os dados enviados. Para se criar uma Classe do tipo Model, são necessárias as seguites observações:
1. Estar definido detro do *namespace App\Models;*
2. Extender a Classe *\LanceMeCore\Database\ModelAbstract;*

### Endpoints
1. [GET] / -> Retorna 200 Ok
2. [GET] /urls/:id -> Retorna 301 redirect. Em caso de registro inexistente, retorna 404 Not Found.
3. [POST] /users/:userid/urls -> Retorna objeto JSON e código 201 Created. Em caso de usuário inexistente, retorna 404 Not Found. Em caso de parametro passado inválido, retorna 205 Reset Content.
4. [GET] /stats -> Retorna objeto JSON
5. [GET] /users/:userId/stats -> Retorna objeto JSON. Em caso de usuário inexistente, retorna 404 Not Found.
6. [GET] /stats/:id -> Retorna objeto JSON. Em caso de registro inexistente, retorna 404 Not Found.
7. [DELETE] /urls/:id -> Retorn vazio em caso de sucesso. Em caso de registro inexistente, retorna 404 Not Found.
8. [POST] /users -> Retorna objeto JSON e código 201 Created. Em caso de usuário inexistente, retorna 404 Not Found. Em caso de parametro passado inválido, retorna 205 Reset Content. __Para correto funcionamento, deve ser enviado um objeto JSON com a seguinte estrutura {"nome" : "NomeDoUsuario"}__
9. [DELETE] /user/:userId -> Retorn vazio em caso de sucesso. Em caso de registro inexistente, retorna 404 Not Found.

### Testes
Os testes são realizados através de clients que fazem o envio de requisições e o envio de respostas que utilizam o formato JSON. Para realizá-los, é possível usar o próprio navegador. Abaixo segue as ferramentas disponíveis para teste de acordo com o Browser usado:
+ Mozilla Firefox - [RESTClient](https://addons.mozilla.org/fr/firefox/addon/restclient/)
+ Google Chrome - [Postman](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop)

### Créditos
Esta aplicação foi orgulhosamente desenvolvida no Elementary OS por Edson B S Monteiro | <bruno.monteirodg@gmail.com>

### Laus Deo!