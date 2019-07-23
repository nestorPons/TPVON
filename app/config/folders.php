<?php 
namespace FOLDERS; 
define('ROOT' ,dirname(dirname(__DIR__)) . '/');

const PUBLIC_ = 'htdocs/'; 
const APP                           = ROOT . 'app/';
    const CONTROLLERS               = APP . 'controllers/';
    const NATIVE_VIEWS              = APP . 'views/';
    const CORE                      = APP . 'core/';
    const MODELS                    = APP . 'models/';
    const CONFIG                    = APP . 'config/';
    const SRC                       = APP . 'src/';
        const LESS                  = SRC . 'less/';
        const JS                    = SRC . 'js/';
    const DB                        = APP . 'db/';
    const HTDOCS                    = ROOT . 'htdocs/'; // Carpeta pública
        const NODE_MODULES              = HTDOCS . 'node_modules/';
        const VIEWS                     = HTDOCS . 'build/';
            const CLASSES_JS            = HTDOCS . 'build/js/';    


namespace VIEWS; 
    const COMPONENTS            = \FOLDERS\VIEWS . 'components/';
    const ADMIN                 = \FOLDERS\VIEWS . 'admin/';
    const USERS                 = \FOLDERS\VIEWS . 'users/';
    const LOGIN                 = \FOLDERS\VIEWS . 'login/';
    
namespace VIEWS\ADMIN; 
    const SECTIONS              = \VIEWS\ADMIN . 'sections/';
    const FORMS                 = \VIEWS\ADMIN . 'sections/forms';

namespace PUBLICF; 
    const JS                     = \FOLDERS\HTDOCS . 'js/';
    const CSS                    = \FOLDERS\HTDOCS . 'css/';
    const COMPANIES              = \FOLDERS\HTDOCS . 'companies/';
    const TEMPLATE               = \FOLDERS\HTDOCS . 'companies/template/';
    const NODE_MODULES           = \FOLDERS\HTDOCS . 'node_modules/';

namespace URL;
    const COMPANIES              =  'companies/';

include \FOLDERS\CONFIG . 'files.php';