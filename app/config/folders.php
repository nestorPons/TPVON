<?php namespace FOLDERS; 
define('ROOT' ,dirname(dirname(__DIR__)) . '/');
const APP           = ROOT . 'app/';
    const CONTROLLERS   = APP . 'controllers/';
    const VIEWS         = APP . 'views/';
    const CORE          = APP . 'core/';
    const MODELS        = APP . 'models/';
    const CONFIG        = APP . 'config/';
    const SRC           = APP . 'src/';
        const LESS          = SRC . 'less/';
        const TS            = SRC . 'ts/';
const HTDOCS        = ROOT . 'htdocs/'; // Carpeta pública
    const JS            = HTDOCS . 'js/';
    const CSS           = HTDOCS . 'css/';
