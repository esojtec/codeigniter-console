# Codeigniter Console
Este paquete ha sido creado probado para **Codeiginiter 3.1.10** pero no debería tener ningún problema en ejecutarse en cualquier versión mayor a Codeigniter 3.0.

## Instalación 

Para comenzar la instalación del paquete **codeigniter console ** debemos clonar o tener el repositorio de **codeigniter 3** o para tener las características de **blade** y **eloquent** de **laravel**  clonar el repositorio de **laraigniter**.

```shell
https://github.com/bcit-ci/CodeIgniter.git
```
__Laraigniter__
```shell
https://github.com/recca0120/laraigniter.git
```
Y agregamos a composer el siguiente repositorio
```shell
{
	"repositories":[{
		"type": "vcs",
		"url": "git@bitbucket.org:esojtec1990/codeigniter-console.git"
	}],
	"require": {
		"php": ">=5.2.4",
		"esojtec/codeigniter-console":"dev-master#v0.9"
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
composer install
```
Copiamos el archivo artisan a la carpeta base
```shell
cp vendor/esojtec/codeigniter-console/src/artisan artisan
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
php artisan make:model --extends=my --eloquent
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
## Nuevos Comandos
Si abrimos el archivo artisan de nuestro repositorio vamos hasta el final del archivo que se encuentra en la carpeta principal y vemos algo como esto.
```php
use Esojtec\CodeigniterConsole\Console;

$console = new Console;
$console->initialize();
// Añadimos un nuevo comando
$console->addCommand(new App\Command\NewCommand());
```
