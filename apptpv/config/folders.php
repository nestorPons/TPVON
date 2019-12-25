<?php 
namespace FOLDERS; 

const PUBLIC_FOLDER_ROOT = 'htdocs/';

const PUBLIC_FOLDER = PUBLIC_FOLDER_ROOT . 'tpv/'; 

const APP                           = ROOT . 'apptpv/';
    const CONTROLLERS               = APP . 'controllers/';
    const NATIVE_VIEWS              = APP . 'views/';
    const CORE                      = APP . 'core/';
    const MODELS                    = APP . 'models/';
    const CONFIG                    = APP . 'config/';
    const SRC                       = APP . 'src/';
        const LESS                  = SRC . 'less/';
        const JS                    = SRC . 'js/';
    const DB                        = APP . 'db/';
    
const HTDOCS                        = ROOT . PUBLIC_FOLDER; // Carpeta pública
    const NODE_MODULES              = HTDOCS . 'node_modules/';
    const VIEWS                     = HTDOCS . 'build/';
        const IMG                   = HTDOCS . 'img/';

namespace VIEWS; 
    const COMPONENTS            = \FOLDERS\VIEWS . 'components/';
    const MYCOMPONENTS          = \FOLDERS\VIEWS . 'mycomponents/';
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

namespace APP; 
    const VIEWS                  = \FOLDERS\APP . 'views/';

namespace APP\VIEWS;
    const COMPONENTS            = \APP\VIEWS . 'components/';