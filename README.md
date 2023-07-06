Segunda Oportunidad es una aplicación de Ventas de Segunda Mano que permite consultar un catálogo de productos, añadir favoritos Desarrollado principalmente en PHP y JavaScript

**_DESARROLLO_**

_BBDD_
   Como SGBD he usado MySQL.
   Dentro de la carpeta (bd/) se incluyen:
	- Fichero de creación de la base de datos con algunos datos de ejemplo (segunda_oportunidad.sql)
	- Scripts que inclyen las fotos de los productos de ejemplo, guardadas en binario (fotos X.sql)
	- Los parametros de conexión con la BBDD se incluyen en (web/Modelo/conexion.php)

_AJAX_
   Múltiples funcionalidades de la aplicación funcionan con Ajax (JavaScript), asi como la paginación de productos, el chat o el sistema de filtrado de productos. Para ello es necesario cambiar las URL's de estos ficheros para que la web funcione correctamente.

const baseURL = "{Ruta del proyecto}/web";

Dentro de (web/Vista/js)
   - chat.js
   - index.js
   - mapa_productos.js
   - tus_favoritos.js
   - tus_productos.js

_MAPA_
   El mapa está desarrollado con la libreria [Leaflet](https://leafletjs.com/) de JS y la api de [GeoApify](https://apidocs.geoapify.com/), la cual te permite hacer peticiones al servidor con una key que te proporcionan ellos.
   


**_PRODUCCION_**
   La aplicación está desplegada en el servidor gratuito de 000webhost. [URL](https://segunda0portunidad.000webhostapp.com/Controlador/controlador_productos.php) 



