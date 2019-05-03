<?php namespace FOLDERS; 
define('ROOT' ,dirname(dirname(__DIR__)) . '/');
const APP               = ROOT . 'app/';
    const CONTROLLERS       = APP . 'controllers/';
    const VIEWS             = APP . 'views/';
        const COMPONENTS        = VIEWS . 'components/'; 
    const CORE              = APP . 'core/';
    const MODELS            = APP . 'models/';
    const CONFIG            = APP . 'config/';
    const SRC               = APP . 'src/';
        const LESS              = SRC . 'less/';
        const JS                = SRC . 'js/';
    const DB                = APP .'db/';
const HTDOCS            = ROOT . 'htdocs/'; // Carpeta pública
    const JSMIN             = HTDOCS . 'js/';
    const CSS               = HTDOCS . 'css/';
    const COMPANIES         = HTDOCS . 'companies/';
