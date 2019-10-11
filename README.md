# TPV ONLINE
Tpv en linea dirigido a las micropymes del sector servicios.
Proyecto desarrollado con una metodología propia de trabajo por lo que no hace uso de fraemworks reconocidos.
Su sistema de objetos es sencillo y facil de implementar y poder crear y destruir partes de la aplicación facilmente. 
Su principales carateristicas son su sencillez de programar y velocidad de ejecución de la aplicación.

## Comenzando 🚀

_Estas instrucciones te permitirán obtener una copia del proyecto en funcionamiento en tu máquina local para propósitos de desarrollo y pruebas._

Mira **Deployment** para conocer como desplegar el proyecto.


### Pre-requisitos 📋

_Disponer de un hosting o servidor LAMP local_
* PHP 7.3.10 o superior 
* MYSQL 8.0
* Docker y docker-compose - para ejecutarlo en local con el servidor que viene incorporado

### Instalación 🔧

_Clone o descargue el proyecto_

```
git clone https://github.com/nestorPons/tpv.git
```

_Entre en la carpeta del proyecto_ 

```
cd tpvonline
```
#### Para desplegarlo en su hosting
_Suba la carpeta descargada a su hosting mediante ftp o git._

**La constante PUBLIC_FOLDER_ROOT declarada en apptpv/config/folders.php contiene la carpeta publica del servidor.**
que por defecto es htdocs

#### Para desplegarlo en local
Si se desea se dispone de un contenedor docker con lamp instalado en el mismo proyecto
Para construirlo por primera vez: 
```
.server/docker-compose build 
```
Para activalo:
```
.server/docker-compose up -d 
```
Para pararlo:
```
.server/docker-compose down
```

Desde el navegador acceda al localhost o su su hosting donde desplegó su aplicación.
```
localhost/tpv o www.tuhosting.com/tpv
``` 
_Se inicia el configurado de la aplicación en su primer acceso._


## Deployment 📦

Consulte su hosting para saber que opciones le proporciona.
Manualmente solo debe copiar la carpeta del prollecto en la localización deseada. 


## Construido con 🛠️

_Herramientas_

* [padrecedano](https://github.com/padrecedano/PHP-PDO) - Conexión base datos con pequeñas modificaciones en la clase.
* [jcavat](https://github.com/jcavat/docker-lamp) - Servidor docker con adaptaciones para el proyecto. 
* [lessphp](https://leafo.net/lessphp/) - Compilador less con php. 
* [linearicons](https://linearicons.com/) - Iconos de la aplicación.
* [cacoo.com](https://cacoo.com) - Diagramas 


## Contribuyendo 🖇️

Por favor lee el [CONTRIBUTING.md](https://gist.github.com/villanuevand/xxxxxx) para detalles de nuestro código de conducta, y el proceso para enviarnos pull requests.

## Wiki 📖

Puedes encontrar mucho más de cómo utilizar este proyecto en nuestra [Wiki](https://github.com/nestorPons/tpv/wiki)

## Versionado 📌

Usamos [SemVer](http://semver.org/) para el versionado. Para todas las versiones disponibles, mira los [tags en este repositorio](https://github.com/tu/proyecto/tags).

## Autores ✒️

_Menciona a todos aquellos que ayudaron a levantar el proyecto desde sus inicios_

* **Andrés Villanueva** - *Trabajo Inicial* - [villanuevand](https://github.com/villanuevand)
* **Fulanito Detal** - *Documentación* - [fulanitodetal](#fulanito-de-tal)

También puedes mirar la lista de todos los [contribuyentes](https://github.com/your/project/contributors) quíenes han participado en este proyecto. 

## Licencia 📄

Este proyecto está bajo la Licencia (Tu Licencia) - mira el archivo [LICENSE.md](LICENSE.md) para detalles

## Expresiones de Gratitud 🎁

* Comenta a otros sobre este proyecto 📢
* Invita una cerveza 🍺 a alguien del equipo. 
* Da las gracias públicamente 🤓.
* etc.



---
⌨️ con ❤️ por [Villanuevand](https://github.com/Villanuevand) 😊
