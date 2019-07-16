# Codeigniter Console
Este paquete ha sido creado probado para **Codeiginiter 3.1.10** pero no debería tener ningún problema en ejecutarse en cualquier versión mayor a Codeigniter 3.0.

## Instalación 

Para comenzar la instalación del paquete **codeigniter console ** debemos clonar el repositorio.

```shell
git clone https://github.com/esojtec/codeigniter-console.git
```
Ejecutamos el comando composer
```shell
composer install
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