# Docker: PHP & MySQL

Instala rápidamente un ambiente de desarrollo local para trabajar con [PHP](https://www.php.net/) y [MySQL](https://www.mysql.com/) utilizando [Docker](https://www.docker.com). 

Utilizar *Docker* es sencillo, pero existen tantas imágenes, versiones y formas para crear los contenedores que hacen tediosa esta tarea. Este proyecto ofrece una instalación rápida, con versiones estandar y con la mínima cantidad de modificaciones a las imágenes de Docker. Viene configurado con `PHP 8.2` y `MySQL 8.0`, además se incluyen las extensiones `gd`, `zip` y `mysql`.

## Requerimientos

* [Docker Desktop](https://www.docker.com/products/docker-desktop)

## Configuración

### 1.- Crear archivo de configuración

Copia el archivo de ejemplo y personaliza las credenciales:

```zsh
cp .env.example .env
```

### 2.- Variables de configuración

Puedes utilizar la configuración por defecto, pero puedes cambiar las opciones directamente. La configuración se ubica en el archivo `.env` con las siguientes opciones:

| Opción                | Valor por defecto  | Descripción                                                                                                                                                      |
|-----------------------|--------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `PROJECT_NAME`        | `php-mysql-dev`      | Nombre del proyecto (afecta nombres de contenedores y redes)                                                                                                     |
| `PHP_VERSION`         | `8.2`                | Versión de PHP ([Versiones disponibles de PHP](https://github.com/docker-library/docs/blob/master/php/README.md#supported-tags-and-respective-dockerfile-links)) |
| `PHP_PORT`            | `80`                 | Puerto para servidor web                                                                                                                                         |
| `MYSQL_VERSION`       | `8.0`                | Versión de MySQL ([Versiones disponibles de MySQL](https://hub.docker.com/_/mysql))                                                                              |
| `MYSQL_USER`          | `developer`          | Nombre de usuario para conectarse a MySQL                                                                                                                        |
| `MYSQL_PASSWORD`      | `developer_password` | Contraseña de acceso del usuario para conectarse a MySQL.                                                                                                        |
| `MYSQL_ROOT_PASSWORD` | `root_password`      | Contraseña de acceso del usuario root de MySQL.                                                                                                                  |
| `MYSQL_DATABASE`      | `app_database`       | Nombre de la base de datos que se crea por defecto.                                                                                                              |
| `MYSQL_PORT`          | `3306`               | Puerto para acceder a MySQL externamente.                                                                                             |

## Instalación

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
* `/www/.htaccess` reglas de reescritura de URLs para Apache.

## Accesos

### 1.- Web

* http://localhost/

### 2.- Base de datos

Existen dos dominios para conectarse a base de datos.

* `mysql`: para conexión desde los archivos PHP.
* `localhost`: para conexiones externas al contenedor.

Las credenciales de ejemplo en `.env.example` son:

| Usuario | Clave | Base de datos |
|:---:|:---:|:---:|
| developer | developer_password | app_database |

**Nota:** Los valores de root son `root_password` para el usuario root y `3306` para el puerto MySQL.

## Ejemplos de uso

### Conexión a MySQL desde PHP

**Usando PDO:**

```php
<?php
$host = 'mysql';  // Nombre del servicio en docker-compose
$db   = getenv('MYSQL_DATABASE') ?: 'app_database';
$user = getenv('MYSQL_USER') ?: 'developer';
$pass = getenv('MYSQL_PASSWORD') ?: 'developer_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa!";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
```

**Usando MySQLi:**

```php
<?php
$host = 'mysql';
$user = 'developer';
$pass = 'developer_password';
$db   = 'app_database';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
echo "Conexión exitosa!";
$conn->close();
?>
```

## Extensiones PHP adicionales

Si necesitas instalar extensiones PHP adicionales, edita el archivo `docker/php/Dockerfile`. Algunos ejemplos:

**Instalar extensión mbstring:**
```dockerfile
RUN docker-php-ext-install mbstring
```

**Instalar extensión intl:**
```dockerfile
RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-install intl
```

**Instalar extensión Redis:**
```dockerfile
RUN pecl install redis \
    && docker-php-ext-enable redis
```

**Instalar extensión imagick:**
```dockerfile
RUN apt-get update && apt-get install -y libmagickwand-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick
```

Después de modificar el Dockerfile, reconstruye la imagen:
```zsh
docker-compose build --no-cache php
docker-compose up -d
```

## Troubleshooting

### El contenedor MySQL no inicia

**Problema:** Error `[ERROR] [MY-010735] Can't open the mysql.plugin table`

**Solución:** Elimina el volumen de MySQL y vuelve a crear:
```zsh
docker-compose down
docker volume rm php-mysql-dev-data
docker-compose up -d
```

### No puedo conectarme a MySQL desde mi aplicación PHP

**Problema:** `SQLSTATE[HY000] [2002] Connection refused`

**Solución:** Asegúrate de usar el nombre del servicio `mysql` como host, NO `localhost`:
```php
$host = 'mysql'; // ✅ Correcto
$host = 'localhost'; // ❌ Incorrecto desde PHP
```

### Error "Permission denied" al acceder a archivos en /www

**Problema:** Los archivos creados desde el contenedor tienen permisos incorrectos.

**Solución en Linux/Mac:** Ajusta los permisos:
```zsh
sudo chown -R $USER:$USER www/
chmod -R 755 www/
```

**Solución en Windows:** Asegúrate de que Docker Desktop tenga acceso a la carpeta compartida en Settings > Resources > File Sharing.

### Puerto 80 o 3306 ya está en uso

**Problema:** `Bind for 0.0.0.0:80 failed: port is already allocated`

**Solución:** Cambia el puerto en el archivo `.env`:
```
PHP_PORT=8080
MYSQL_PORT=3307
```

Luego reinicia:
```zsh
docker-compose down
docker-compose up -d
```

### Cambios en el código PHP no se reflejan

**Problema:** Los cambios no aparecen al recargar el navegador.

**Solución:** Verifica que el volumen esté montado correctamente y limpia la caché de Apache:
```zsh
docker-compose restart php
```

Si persiste, reconstruye la imagen:
```zsh
docker-compose build --no-cache php
docker-compose up -d
```

### Error al construir imagen: "Package not found"

**Problema:** Falla la instalación de dependencias en el Dockerfile.

**Solución:** Actualiza la lista de paquetes y reconstruye:
```zsh
docker-compose build --no-cache --pull
```
