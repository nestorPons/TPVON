# INFORMACIÓN TÉCNICA
###### _Documento dirigido a desarrolladores._
___

### Listado de carpetas

> **apptpv** --> _Aplicación TPVON._      
>> **caches** --> _Contenedor de archivos de caché._         
>> **config** --> _Archivos de configuración de la aplicación._     
>>> app.php --> _Archivo de configuración de la aplicación._            
>>> conndefault --> _Datos predeterminados de la configuración._
>>> conn.ini --> _Configuración de la conexión._
>>> files.php --> _Constantes de direcciónes de archivos._    
>>> folders.php --> _Constantes de direcciones de carpetas._      
>>> mail.php --> _Configuración para la clase mail._      

>> **controllers** --> _Clases controladoras._   
>> **core** --> _Clases y archivos esenciales del fraemwork._ 
>>> Components.php --> _Clase que crea los componentes._
>>> Controller.php --> _Clase controladora principal._    
>>> Data.php --> _Clase auxiliar de control de datos._   
>>> Error.php --> _Clase para el control de errores (deshuso)._      
>>> Preprocessor.php --> _Clase preprocesadora de las plantillas._    
>>> Query.php --> _Clase de conexión a la base de datos._
>>> Router.php --> _Clase enrutadora._
>>> Security.php --> _Clase que se encarga de la seguridad de la aplicación._ 

>> **db** --> _Carpeta contenedora de los archivos SQL._  
>> **helpers** --> _Scripts de php._  
>> **libs** --> _Clases de terceros que no han sido cargadas con composer._   
>> **models** --> _Clases modelo._    
>> **src** --> _Archivos y carpetas js y css para ser compilados._    
>>> **js** --> _Archivos JS._   
>>> **less** --> _Archivos less sin compilar._   

>> **view** --> _Carpeta de las vistas._      
>>> **mycomponents** --> _Carpeta donde se guardan los componentes creados._    
>>> **admin** --> _Carpeta de las vistas de la zona de administrador._
>>> **components** --> _Versión 1.0 de los conmponentes (desuso)._   
>>> **login** --> _Todas las vistas de la zona de login._     
>>> **users** --> _Vistas de la zona de usuarios cliente (inactiva)._

>> **docs** --> _Carpeta de documentación creada por [MkDocs](https://www.mkdocs.org/)._    
>> **htdocs** --> _Carpeta pública._
>>> **tpv** --> _Se usa una carpeta para añadir la aplicación en un host con otros elementos._
>>>> **buid** --> _Se almacena el post-procesado de la aplicación._  
>>>> **companies** --> _Se guardan los archivos de configuración editables por el usuario._     
>>>> **config** --> _Scripts y htmls de configuración en la creación de la aplicación._
>>>> **css** --> _Post-procesado de los archivos de estilos._    
>>>> **js** --> _Post-procesado de los archivos js._
>>>> **node_modules** --> _Contiene los paquetes npm._ 
>>>> index.php --> _Puerta principal de enlace a la aplicación._

>> **site** --> _Archivos compilados por [MkDocs](https://www.mkdocs.org/)._
>> **vendor** --> _Carpeta de almacenamiento de [Composer](https://getcomposer.org//)._

!!! Info note 
    Percatese que los nombres de archivos de clase se escriben con la primera letra en mayúsculas. 