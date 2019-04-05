(IMAGEN LOGO TPV)

TPV OnLine


Proyecto creado por Néstor Pons Portolés 
DAW

Indice 
1. Introducción
	1.1 Descripción
	1.2 Características
	1.3 Ciclos del proyecto
		
	
2. Creación de entorno
	1.1 Necesidades
	1.2 Lenguajes de programación
	2.2 
1.1 Descripción
TPV OnLine es una aplicación web que gestiona la caja registradora de un establecimiento orientado al sector servicios preferiblemente pero tiene soporte para productos. Es una aplicación escalable, multiplataforma y multiusuario para un buen control de los ingresos diarios de las pequeñas empresas que necesitan de una buena gestión de caja. 
 Un terminal intuitivo y de fácil manejo que ofrece un flujo de trabajo cómodo, rápido y seguro. 
Buscamos crear una herramienta ligera, rápida y funcional sin cargarla de efectos, ni funciones inecesarias que generen pesadez o relenticen nuestro trabajo. 

1.2 Características

La aplicación va a estar orientada a la multiempresa y así desde un mismo dominio poder administrar distintas empresas para ello tendremos que dar mucha importancia a la personalización de estilos y del entorno así como ofrecer distintos flujos de trabajo para que el usuario adopte el que más se adapte a sus necesidades.
1.3 Ciclos del proyecto

2. Creación del entorno
2.1 Necesidades
Por las caracteristicas del proyecto es necesario un entorno limpio y sencillo no se busca fraemworks pesados con muchas caracteristicas, más bien nos enfocaremos en aquellos que ofrezcan sencillez y se ajusten más a las necesidades del proyecto, procuraremos cargar en el cliente solo aquellos recursos indispensables. 

2.2 Tecnologías, lenguajes de programación y herramientas
Utilizaremos las siguientes tecnologías y herramientas para la elavoración del proyecto: 
    • OS Linux x64 4.16.18-rt12-MANJARO .- Sistema operativo liviano y estable
    • XFCE .- Sistema de ventanas muy liviano y altamente configurabl
    • Visual Studio Code 1.32.3 .- 	IDE con muchisimas extensiones, liviana y muy configurable. 
    • Chrome 66.0.3359.181 y Firefox Developer Edition 66.0b13 (64-bit) .- Ambas platadormas son las mas utilidas del mercado  
    • Node.js  10.2.0 y NPM 6.7.0 .- Manejador de paquetes para la parte de cliente 
    • Composer .- Manejador de paquetes para la parte del servidor 
    • MySQL .- Gestor de base de datos 
    • Apache .- Servidor 
    • Less .- 	Sencillo y con su librería de compilación mediante php podemos embeber variables de php en el codigo less para aumentar la personalización de la aplicación para que cada empresa pueda tener su aplicación con caracteristicas propias.  
    • TypeScript .- Optamos por esta capa superior a ecmas 6 por su tipado más solido y algunas mejoras en el diseño, junto a VSC es una excelente combinacion que facilita mucho las tareas de desarroyo.   
    • PHP .-  Tiene todas las librerias necesarias para el proyecto, una documentación impecable una comunidad consolidada desde hace muchos años y es un lenguaje solido y estructurado.
  
2.2 Estructura
La estructura del proyecto se basa en un model MVC avanzado, no usaremos un fraemwork ya que será altamente personalizado a las necesidares del proyecto y las caracteristicas del desarrollador.

Raiz .- En la raiz tendremos los archivos de control de versiones y manejadores de dependencias 
	Para gestionar nuestras dependencias de php vamos a usar Compose por su simpliciad, 	comodidad y principal herramienta de dependencias para este lenguaje de programación.
	Para la parte del cliente será bower el encargado de gestionar las dependencias javascript.
App .-  Es el cuerpo de la aplicación zona no accesible desde el navegador
	config .- Toda las variabres de la configuración del proyecto
	controllers .- Los controladores del modelo MVC
	core .- Las clases generales e indispensables para el funcionamiento del mismo
	db .- Todos los scripts y sentencias sql para generar nuestra base de datos y tablas en mysql
	models .- Los modelos del MVC
	views .- Las vistas del modelo MVC
Htdocs .- Carpeta pública
	Build .- Carpeta contenedora de los archivos compilados de css, js así como aquellas imagenes 	que necesiten de compilación o transformación para ser utilizadas en la aplicación.
	Js .- Carpeta contenedora de todos los scrips y clases JavaScript. 
	Css .- Carpeta con los archivos de estilos en formato Less 
Doc .- Documentación del proyecto y la aplicación. 