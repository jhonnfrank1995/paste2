======================================================================
  Guía de Instalación de PasteX Pro
======================================================================

Sigue estos pasos para instalar PasteX Pro en tu servidor. Esta guía
asume que tienes un conocimiento básico sobre la gestión de un
servidor web.


1. Requisitos del Servidor
----------------------------------------------------------------------
Antes de comenzar, asegúrate de que tu servidor cumple con los
siguientes requisitos:

- PHP 8.2 o superior con las siguientes extensiones habilitadas:
  - pdo_mysql (o pdo_sqlite)
  - mbstring
  - openssl
  - json
  - curl
  - gd
  - intl

- Servidor Web: Apache con `mod_rewrite` activado, o Nginx.

- Base de Datos:
  - MySQL 5.7+ o MariaDB 10.3+
  - O SQLite 3+

- Composer: Para instalar las dependencias de PHP.
  (Ver cómo instalar Composer en https://getcomposer.org/download/)

- Acceso a la línea de comandos (SSH) es altamente recomendado para
  una instalación más fluida.


2. Pasos de Instalación
----------------------------------------------------------------------

Paso 2.1: Subir los Archivos
----------------------------
Descarga el archivo `.zip` de la última versión de PasteX Pro y
súbelo al directorio de tu elección en tu servidor (por ejemplo,
`public_html`, `www`, o `var/www/`). Descomprímelo.

La estructura de archivos debería verse así:
/path/to/your/site/
├── app/
├── bootstrap/
├── config/
├── public/
├── ...
└── composer.json


Paso 2.2: Instalar Dependencias con Composer
--------------------------------------------
Conéctate a tu servidor vía SSH, navega al directorio donde
descomprimiste los archivos y ejecuta Composer. Esto descargará e
instalará todas las librerías de PHP que el script necesita.

  # Navega al directorio raíz de tu proyecto
  cd /path/to/your/site/

  # Instala las dependencias de producción y optimiza el autoloader
  composer install --no-dev --optimize-autoloader


Paso 2.3: Crear la Base de Datos
---------------------------------
PasteX Pro necesita una base de datos para funcionar. Usando tu
herramienta de gestión de bases de datos (como phpMyAdmin, Adminer,
o la línea de comandos), **crea una nueva base de datos vacía**.
También, crea un usuario de base de datos y otórgale todos los
privilegios sobre esa nueva base de datos.

Apunta estos datos, los necesitarás durante la instalación web:
- Nombre de la base de datos
- Nombre de usuario de la base de datos
- Contraseña del usuario


Paso 2.4: Configurar el Servidor Web
------------------------------------
¡Este es el paso más importante para la seguridad! Debes configurar
tu servidor web para que el **DocumentRoot** (o `root`) apunte al
directorio `/public` de PasteX Pro, no al directorio raíz.

  Para Apache:
  El archivo `.htaccess` que se encuentra dentro del directorio
  `/public` debería gestionar las reescrituras de URL
  automáticamente. Solo asegúrate de que tu configuración de host
  virtual apunte al directorio correcto.

  Ejemplo de configuración de VirtualHost en Apache:
  <VirtualHost *:80>
      ServerName yourdomain.com
      DocumentRoot "/path/to/your/site/public"
      <Directory "/path/to/your/site/public">
          AllowOverride All
          Require all granted
      </Directory>
  </VirtualHost>

  Para Nginx:
  Añade un nuevo bloque de `server` a tu configuración de Nginx.

  Ejemplo de configuración para Nginx:
  server {
      listen 80;
      server_name yourdomain.com;
      root /path/to/your/site/public; # ¡Apunta a la carpeta public!
      index index.php;

      location / {
          try_files $uri $uri/ /index.php?$query_string;
      }

      location ~ \.php$ {
          include snippets/fastcgi-php.conf;
          # Ajusta la siguiente línea a tu configuración de PHP-FPM
          fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
      }

      location ~ /\. {
          deny all;
      }
  }
  Recuerda reiniciar Nginx después de guardar los cambios.


Paso 2.5: Establecer Permisos de Directorio
-------------------------------------------
El servidor web necesita permisos para escribir en el directorio `storage`
y para crear el archivo `.env` en la raíz.

  # Navega a la raíz del proyecto
  cd /path/to/your/site/

  # Da permisos de escritura al directorio de storage
  chmod -R 775 storage

  # Da permisos de escritura al directorio raíz
  chmod 775 .

  # Opcional: Asigna la propiedad al usuario del servidor web (ej. www-data)
  # chown -R www-data:www-data storage
  # chown www-data:www-data .


Paso 2.6: Ejecutar el Instalador Web
------------------------------------
Ahora, abre tu navegador y navega a `http://yourdomain.com/install`.

El asistente de instalación te guiará a través de los pasos finales:
1. Comprobación del Servidor: Verifica que todos los requisitos están cumplidos.
2. Configuración de la Base de Datos: Introduce los datos de la base
   de datos que creaste en el Paso 2.3.
3. Creación del Administrador: Configura tu cuenta de administrador principal.
4. Finalización: El instalador creará tu archivo `.env` y poblará la
   base de datos.


Paso 2.7: ¡CRÍTICO! Eliminar el Directorio de Instalación
----------------------------------------------------------
Una vez que el instalador haya finalizado con éxito, por razones de
seguridad, **DEBES ELIMINAR EL DIRECTORIO `/install` DE TU SERVIDOR.**

  # Desde la raíz del proyecto
  rm -rf install

También es una buena práctica restaurar los permisos del directorio
raíz a 755 si los cambiaste: `chmod 755 .`


Paso 2.8: Configurar el Cron Job
--------------------------------
Para que las tareas automáticas (como borrar pastes expirados)
funcionen, debes añadir un cron job. Edita tu crontab (`crontab -e`)
y añade la siguiente línea:

  * * * * * cd /path/to/your/site && php cron.php >> /dev/null 2>&1

Este comando se ejecutará cada minuto, y el script `cron.php` decidirá
si hay alguna tarea de mantenimiento que deba realizarse.


3. Post-Instalación
----------------------------------------------------------------------
¡Felicidades! Tu instancia de PasteX Pro está lista para usarse.

- Accede al sitio en: http://yourdomain.com
- Inicia sesión como administrador en: http://yourdomain.com/login

Se recomienda ir al Panel de Administración y revisar las
configuraciones en "Site Settings" para personalizar tu sitio.