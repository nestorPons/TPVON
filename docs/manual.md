# MANUAL DE USUARIO
###### _Documento dirigido a los usuarios finales de TPVON._
___

## Nueva aplicación
Pagina a la que se accede la primera vez que se inicia la aplicación y se han introducido los datos de configuración de la base de datos y servidor de emails. 
En esta hay que rellenar los datos de la empresa y del usuario super que será con el que se accederá por primera vez al dashboard. 
Los datos aquí introducidos podrán ser editados posteriormente en la sección configuración. 

## Login 
> _Página inicial de la aplicación._

En esta tenemos que loguearnos introduciendo el email con el que nos registramos y la contraseña. 
Tenemos un acceso al reinicio de la contraseña por si nos hemos olvidado de ella. 

### Recuperar contraseña 
Si hemos configurado correctamente el servidor de emails. Podremos crear una  nueva contraseña y nos envía un email con un reseteo de contraseña. 

## Dashboard
Panel principal de la aplicación
Se compone de: 

* Barra de tareas ( Parte superior )
* Barra navegación ( Parte izquierda )
* Marco de secciones ( Centro )

### TPV
###### Nivel de acceso: usuario.

Zona principal donde se crean las facturas simplificadas de compra. 
!!! Importante warning
    Antes de crear facturas simplificadas deberá rellenar todos los campos relativos a la empresa en la sección de configuración. Además deberá rellenar los datos de las familias, los servicios y los usuarios.

### Usuarios
###### Nivel de acceso: usuario.

En esta sección se pueden crear, eliminar y modificar los usuarios y los clientes. 
Para crear y editar clientes se debe ser rol de usuario y para crear y editar usuarios se debe ser administrador que solo pueden ser creados por otro administrador.

AL iniciarse la base de datos se crean dos usuarios por defecto: 

* __El usuario administrador__, es el mismo con el que se ha registrado al crear el programa.
* __Usuario invitado__, un usuario comodín.

!!! Consejo tip
    Se recomienda no borrar ni modificar el usuario invitado. 

### Servicios
###### Nivel de acceso: usuario.

En esta sección se listan los servicios y se pueden hacer tareas de mantenimiento y creación para los mismos. 
!!! Importante warning 
    Antes de crear los servicios es conveniente haber creado todas las familias.

### Familias
###### Nivel de acceso: usuario.

Sección para crear, editar y eliminar las familias de los servicios o productos. 
Estas familias agrupan los servicios o artículos por grupos mayores para una mejor organización y un posterior analisis de los rendimientos del comercio. 

### Regalo
###### Nivel de acceso: usuario.

Listado y gestión de los tickets que se destinan para regalo. Como existe la posibilidad de realizarse los servicios por separado se ha implementado un seguimiento de las fechas en la que se han consumido los servicios.  
!!! Recuerda info
    Desde la barra de tareas el acceso "imprimir" se pueden imprimir los Cheque Regalo. 

Estos son una nota informativa para la persona a la que se le hace el regalo y así poder regalar un objeto tangible con el que poder dar valor al acto. 

### Impagos
###### Nivel de acceso: usuario.

Listado de las deudas acumuladas por los servicios ya realizados.
Seleccionando la deuda deseada se acederá directamente a la pantalla inicial tpv y se podrá eliminar la deuda accediendo al ticket y pulsando el boton eliminar deuda. 

### Config
###### Nivel de acceso: administrador. 

La distinta configuración para un correcto funcionamiento de  la aplicación. 
Se diferencian 4 sub-secciones: 

* Configuración de la empresa .- Todo lo concerniente a la empresa. Todos estos datos son mostrados en las facturas simplificadas. 
* Datos para la aplicación .- Tipo impositivo, días por defecto para la validez del cheque regalo. Este dato se puede cambiar individualmente en la creación del cheque regalo.
* Promoción fidelización .- Se aplica un descuento a los clientes que consumen nuestros servicios de una forma regular.  
* Gerente de la empresa .- Indicaremos el usuario que es el gerente de la empresa. Debe tener privilegios de administrador.

### Historial
###### Nivel de acceso: administrador.

Lugar donde podemos consultar los ingresos en un periodo de tiempo y poder imprimir los ingresos trimestrales. 
Tambien disponemos de un filtro para saber cuanto ha recaudado cada trabajador. 

### Gráficas
###### Nivel de acceso: administrador.
Sección donde se muestran las gráficas de ingresos por periodo de tiempo y una tendencia para el siguiente periodo creada mediante IA.
Cuanto mayor sea la muestra analizada mejorará la predicción del sistema. 