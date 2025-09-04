# Content Expiry

Permite establecer una fecha de caducidad en las entradas de WordPress.  
Mientras el contenido está activo, se muestra un aviso indicando cuánto tiempo queda antes de que expire.  

## Descripción

**Content Expiry** añade un campo de fecha a las entradas para indicar cuándo deben caducar.  
Cuando un usuario accede a un post con fecha de caducidad configurada, se muestra un mensaje que indica el tiempo restante hasta que el contenido deje de estar disponible.  

Cuando llega la fecha de caducidad, el post se despublica automáticamente.  

Características principales:
- Campo adicional en los posts para establecer la fecha de caducidad.  
- Aviso dinámico en el frontend mostrando el tiempo restante.  
- Despublicación automática del contenido al llegar la fecha indicada.  
- Configuración sencilla y funcionamiento automático.  

## Instalación

1. Subir la carpeta `dl-content-expiry` al directorio `/wp-content/plugins/`.  
2. Activar el plugin desde el menú *Plugins* en WordPress.  
3. Editar cualquier entrada y establecer la fecha de caducidad desde el nuevo campo disponible.  

## Uso

1. Edita una entrada y selecciona una fecha de caducidad.  
2. Guarda la entrada.  
3. Los visitantes verán un mensaje con el tiempo restante hasta la caducidad.  
4. Al cumplirse la fecha, el post se despublicará automáticamente.  

## Requisitos

- WordPress 6.0 o superior.  
- PHP 7.4 o superior.  

## Licencia

GPL-2.0 o posterior  
https://www.gnu.org/licenses/gpl-2.0.html
