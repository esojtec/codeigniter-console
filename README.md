# Codeigniter Console
Este paquete ha sido creado probado para **Codeiginiter 3.1.10** pero no debería tener ningún problema en ejecutarse en cualquier versión mayor a **Codeigniter 3.0**.

## Instalación 

Para comenzar la instalación del paquete **codeigniter console ** debemos modificar nuestro composer y agregamos las siguientes lineas.

```shell
    "repositories":[{
		"type": "vcs",
		"url": "https://github.com/esojtec/codeigniter-console.git"
    }],
	"require": {
		"esojtec/codeigniter-console":"dev-master#v1.0"
	},
	"autoload": {
        "psr-4": {
            "App\\Models\\": "application/models",
			"App\\Commands\\": "application/commands"
        }
    }
```
Ejecutamos el comando composer
```shell
composer install or composer update
```
Copiamos el archivo artisan a la carpeta principal
```shell
cp vendor/esojtec/codeigniter-console/src/artisan artisan
```
y finalmente ejecutamos deploy que modificara config y creara el controlador para realizar las migraciones
```shell
php artisan deploy:migration
```
## Comandos
Con este comando podremos ver los comandos disponibles
```shell
php artisan --help
```
**Controlador**
```shell
php artisan make:controller ControllerName --extends=my --model=UserModel
```
**Modelo**
```shell
php artisan make:model ModelName --extends=my --eloquent
```
**Librerias**
```shell
php artisan make:library LibraryName --config
```
**Vista**
```shell
php artisan make:view prueba.welcome --blade --layout=layouts.master
```
**Migraciones**
```shell
php artisan migrate
```
**Rollback**
```shell
php artisan migrate:rollback --steps=3
```
**Crear Comando**
```shell
php artisan make:command
```
**Generacion de clave de encriptación**
```shell
php artisan key:generate
```
## Nuevos Comandos
Los nuevos comandos se crean por medio de la instruccion **make:command** por medio del generador de comandos, esta nueva clase se guarda dentro de la carpeta **application/commands**.
Una vez creada debemos cargarla atraves del archivo **artisan** localizado en la carpeta principal, nos dirigimos al final del archivo donde encontraremos lo siguiente:
```php
use Esojtec\CodeigniterConsole\Console;

$console = new Console;
// Añadimos un nuevo comando
$console->addCommand(new App\Commands\NewCommand());
$console->run();
```
Ahí agregaremos los nuevos comandos de nuestra aplicación con la instruccion *$console->addCommand(new App\Commands\NewCommand());*
> La clase debe estar instanciada por medio de \Symfony\Component\Console\Command\Command
> Para generar la clave con la instruccion *php artisan key:generate* es necesario activar la extension "extension=php_gmp.so"