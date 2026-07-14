# SISTEDOC

Sistema de Gestión de Contrataciones MSPBS.

## Installation

Clonar la aplicación
```bash
$ git clone https://gitlab.com/sistemas.dgtic/sistedoc.git
```
Crear archivo .env (copiando el archivo de ejemplo .env.example)
```bash
$ cp .env.example .env
```
Rellenar los campos de configuración del .env
```bash
$ gedit .env
```
En caso de necesidad modificar puerto donde se ejecuta el nginx y el server name
```bash
$ gedit docker-compose.yml
$ gedit docker_files/nginx/default.conf
```
Crear imágenes y desplegar los contenedores
```bash
$ docker-compose up -d
```
Ejecutar composer install (Para instalar las dependencias del proyecto)
```bash
$ docker exec php php /usr/local/bin/composer install
```
Generar tablas y rellenar las tablas básicas con los datos de los seeders
```bash
$ docker exec php php artisan migrate --seed
```
Crear enlace simbólico a la carpeta storage (para subir archivos al servidor, escribir rutas completas)
```bash
$ docker exec php ln -s /var/www/html/storage/app/public /var/www/html/public/storage
```
Asignar como dueño del directorio del proyecto al usuario www-data
```bash
$ sudo chown -R www-data:www-data /var/www
```

## Usage
Acceder al proyecto a través del puerto 80
[http://localhost](http://localhost)

## Contributing
Las descargas del proyecto están habilitadas exclusivamente a la Dirección de Sistemas del MSPBS.

Por favor asegurarse de tener la última versión del proyecto apropiadamente.