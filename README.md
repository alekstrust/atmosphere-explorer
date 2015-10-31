Atmosphere Explorer
===================

*Este documento está incompleto.*

Este software analiza los datos almacenados en una cuenta de correo electrónico, generados por un SYMPHONIEPLUS®3 DATA LOGGER de Renewable NRG Systems. Los archivos .RWD son procesados y analizados para luego ser insertados en una base de datos MySQL.
Se incluye un plugin para Wordpress que permite la visualización por 'shortcodes'.

Úsalo, modifícalo, pero no lo vendas.

**Este software se provee de forma gratuita y sin ninguna garantía, bajo licencia GPL2.** 

----------

Requisitos
-------------
 - PHP 5.5+
 - MySQL 5.6+
 - Una cuenta de correo con soporte IMAP.
 - [Symphonie Data Retriever Software](https://www.renewablenrgsystems.com/services-support/documentation-and-downloads/software-downloads/detail/symphonie-data-retriever-software) propiedad de  Renewable NRG Systems (no se incluye en este paquete).

Componentes
-------------
#### Parser
Descarga, convierte, analiza y almacena los datos generados por los 'data loggers' de NRG. Esta parte se ejecuta sobre una plataforma Windows con Symphonie Data Retriever instalado (aunque también es posible ejecutarlo en sistemas UNIX con Wine y algunos trucos).

> **Nota:**

> - Se provee un archivo config-sample-php. Renómbralo a config.php y personalízalo para ejecutar el sistema.
> - El script start.php es el punto de entrada. Puedes ejecutar 'php.exe start.php'.

#### Atmosphere Explorer Viewer
Un plugin para Wordpress que permite ver la información almacenada por cada Logger y sus sensores en gráficos.

No tiene una interfaz para la administración aún.

Usa los shortcodes [aeviewer_list] y [aeviewer_logger].

> **Nota:**

> - Modifica el archivo db.php con tu conexión a la base de datos.