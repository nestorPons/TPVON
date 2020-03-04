<?php
/**
 *
 * Licencias
 *
 * Se concede permiso por la presente, libre de cargos, a cualquier persona que obtenga
 * una copia de este software y de los archivos de documentación asociados (el "Software"),
 * a utilizar el Software sin restricción, incluyendo sin limitación los derechos a usar, copiar,
 * modificar, fusionar, publicar, distribuir, sublicenciar, y/o vender copias del Software, y a
 * permitir a las personas a las que se les proporcione el Software a hacer lo mismo, sujeto a
 * las siguientes condiciones:
 * 
 * El aviso de copyright anterior y este aviso de permiso se incluirán en todas las copias o
 * partes sustanciales del Software.
 * 
 * EL SOFTWARE SE PROPORCIONA "COMO ESTÁ", SIN GARANTÍA DE NINGÚN
 * TIPO, EXPRESA O IMPLÍCITA, INCLUYENDO PERO NO LIMITADO A GARANTÍAS
 * DE COMERCIALIZACIÓN, IDONEIDAD PARA UN PROPÓSITO PARTICULAR E
 * INCUMPLIMIENTO. EN NINGÚN CASO LOS AUTORES O PROPIETARIOS DE LOS
 * DERECHOS DE AUTOR SERÁN RESPONSABLES DE NINGUNA RECLAMACIÓN,
 * DAÑOS U OTRAS RESPONSABILIDADES, YA SEA EN UNA ACCIÓN DE
 * CONTRATO, AGRAVIO O CUALQUIER OTRO MOTIVO, DERIVADAS DE, FUERA DE
 * O EN CONEXIÓN CON EL SOFTWARE O SU USO U OTRO TIPO DE ACCIONES EN
 * EL SOFTWARE.
 * 
 * @author    Nestor Pons <nestorpons@gmail.com>
 * @copyright 2019 Nestor Pons y contribuidores
 * @license   https://opensource.org/licenses/MIT
 */

use \app\core\{Router, Prepocessor};
use \app\controllers\{Styles}; 
define('ROOT' ,dirname(dirname(__DIR__)) . '/');
require_once ROOT . '/apptpv/config/folders.php';
require_once ROOT . '/apptpv/config/app.php';
require_once ROOT . '/vendor/autoload.php';  
include \FOLDERS\CONFIG . 'files.php';

// Desarrollo solo se ejecuta en la puerta principal en la primera petición
if( ENV && empty($_REQUEST)){
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    require_once \FOLDERS\HELPERS  . 'dev.php';
    Styles::load();
    require_once \FOLDERS\HELPERS  . 'minify.php';
    
    new Prepocessor(false);
}

new Router($_REQUEST);