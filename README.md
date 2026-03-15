# Docker: PHP & MySQL

Instala rápidamente un ambiente de desarrollo local para trabajar con [PHP](https://www.php.net/) y [MySQL](https://www.mysql.com/) utilizando [Docker](https://www.docker.com). 

Utilizar *Docker* es sencillo, pero existen tantas imágenes, versiones y formas para crear los contenedores que hacen tediosa esta tarea. Este proyecto ofrece una instalación rápida, con versiones estandar y con la mínima cantidad de modificaciones a las imágenes de Docker. 

Viene configurado con `PHP 8.2` y `MySQL 8.0`, además se incluyen las extensiones `gd`, `zip` y `mysql`.

## Requerimientos

* [Docker Desktop](https://www.docker.com/products/docker-desktop)

## Configurar el ambiente de desarrollo

### Primer paso: Crear archivo de configuración

Copia el archivo de ejemplo y personaliza las credenciales:

```zsh
cp .env.example .env
```

**⚠️ IMPORTANTE:** Por seguridad, el archivo `.env` contiene credenciales y NO debe ser versionado en git. Modifica los valores antes de usar en producción.

### Variables de configuración

Puedes utilizar la configuración por defecto, pero en ocasiones es recomendable modificar la configuración para que sea igual al servidor de producción. La configuración se ubica en el archivo `.env` con las siguientes opciones:

* `PROJECT_NAME` nombre del proyecto (afecta nombres de contenedores y redes).
* `PHP_VERSION` versión de PHP ([Versiones disponibles de PHP](https://github.com/docker-library/docs/blob/master/php/README.md#supported-tags-and-respective-dockerfile-links)).
* `PHP_PORT` puerto para servidor web.
* `MYSQL_VERSION` versión de MySQL ([Versiones disponibles de MySQL](https://hub.docker.com/_/mysql)).
* `MYSQL_USER` nombre de usuario para conectarse a MySQL.
* `MYSQL_PASSWORD` clave de acceso del usuario para conectarse a MySQL.
* `MYSQL_ROOT_PASSWORD` clave de acceso del usuario root de MySQL.
* `MYSQL_DATABASE` nombre de la base de datos que se crea por defecto.
* `MYSQL_PORT` puerto para acceder a MySQL externamente (comentar para no exponer).

## Instalar el ambiente de desarrollo

La instalación se hace en línea de comandos:

```zsh
docker-compose up -d
```
Puedes verificar la instalación accediendo a: [http://localhost/info.php](http://localhost/info.php)

## Comandos disponibles

Una vez instalado, se pueden utilizar los siguiente comandos:

```zsh
docker-compose start    # Iniciar el ambiente de desarrollo
docker-compose stop     # Detener el ambiente de desarrollo
docker-compose down     # Detener y eliminar el ambiente de desarrollo.
```

## Estructura de Archivos

* `/docker/` contiene los archivos de configuración de Docker.
* `/www/` carpeta para los archivos PHP del proyecto.

## Accesos

### Web

* http://localhost/

### Base de datos

Existen dos dominios para conectarse a base de datos.

* `mysql`: para conexión desde los archivos PHP.
* `localhost`: para conexiones externas al contenedor.

Las credenciales de ejemplo en `.env.example` son:

| Usuario | Clave | Base de datos |
|:---:|:---:|:---:|
| developer | developer_password | app_database |

**Nota:** Los valores de root son `root_password` para el usuario root y `3306` para el puerto MySQL.
