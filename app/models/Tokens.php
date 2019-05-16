<?php namespace app\models;
use \Firebase\JWT\JWT;

class Tokens extends \app\core\Query{
    protected $id, $idUsuario, $token;
    protected $table = 'tokens';
    
    function __construct($args = null){
        parent::__construct();
        if($args){
            if (is_int($args)) $this->searchById($args);
        }
    }
    private function searchById(Object $data){
        
    }
    function create(Object $User){

        $time = time();
        
        $arr = array(
            'iat' => $time, // Tiempo que inició el token
            'exp' => $time + (60*60), // Tiempo que expirará el token (+1 hora)
            'data' => [ // información del usuario
                'id' => $User->id()
            ]
        );
        $token = JWT::encode($arr, KEY_JWT);
        $this->id = $this->add(['id_usuario'=>$User->id(), 'token' => $token]);
        return $token;
    }
    static function decode(String $token){
        return JWT::decode($token, KEY_JWT, array('HS256'))->data;
    }
}