# Guía de Actualización de PasteX Pro

Sigue estos pasos para actualizar tu instancia de PasteX Pro a la última versión.

**¡Importante! Antes de empezar, siempre haz una copia de seguridad completa de tus archivos y de tu base de datos.**

## Pasos de Actualización

1.  **Activar el Modo Mantenimiento:**
    Desde el Panel de Administración, activa el modo mantenimiento para prevenir que los usuarios accedan al sitio durante la actualización.

2.  **Hacer Copia de Seguridad:**
    -   **Archivos:** Comprime todo el directorio de tu instalación de PasteX Pro.
    -   **Base de Datos:** Utiliza una herramienta como `mysqldump` para exportar tu base de datos a un archivo SQL.

3.  **Subir los Nuevos Archivos:**
    -   Descarga el archivo `.zip` de la nueva versión de PasteX Pro.
    -   Elimina los siguientes directorios de tu instalación actual: `app/`, `bootstrap/`, `config/`, `public/`, `resources/`, `tools/`, `vendor/`.
    -   **NO elimines** tu archivo `.env` ni el directorio `storage/`.
    -   Sube y descomprime los nuevos directorios desde el archivo `.zip` de la nueva versión.

4.  **Actualizar Dependencias:**
    Conéctate por SSH y ejecuta Composer para instalar cualquier dependencia nueva o actualizada.

    ```bash
    composer install --no-dev --optimize-autoloader
    ```

5.  **Ejecutar Migraciones de Base de Datos:**
    Si la nueva versión incluye cambios en la base de datos, necesitarás ejecutar el script de migración. (En futuras versiones, esto se podrá hacer desde el Panel de Admin o un comando CLI).

    *Para la v1.0, este proceso es manual. Consulta el `CHANGELOG.md` para cualquier cambio en la DB.*

6.  **Limpiar Caché (Opcional):**
    Si la aplicación utiliza caché de vistas o de configuración, bórrala.
    *Actualmente, no se requiere para la v1.0.*

7.  **Desactivar el Modo Mantenimiento:**
    Una vez que hayas verificado que el sitio funciona correctamente, desactiva el modo mantenimiento desde el Panel de Administración.

¡Tu instancia de PasteX Pro está actualizada!