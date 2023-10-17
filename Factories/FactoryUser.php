<?php

class FactoryUser {
    static $nombre = ["Juan", "María", "Pedro", "Ana", "Luis", "Laura", "Carlos", "Sofía", "Diego", "Isabella"];
    static $email = ["juan@fabrica.com", "maria@industrias.net", "pedro@producciones.xyz", "ana@fabricante.org", "luis@fabricaworks.biz", "laura@manufacturas.co", "carlos@fabricacorp.info", "sofia@factory-inc.com", "diego@producciontotal.biz", "isabella@industrialab.net"];

    static function generateUser(){
        $user = new Usuario();
        $name = self::$nombre[rand(0,count(self::$nombre))];
        $mail = self::$email[rand(0,count(self::$email))];
        $v = [$name, "1234", $mail, 0];
        $user->setUser($v);
        return $user;
    }

    static function generateUsers($num){

        $users = [];
        $i = 0;

        while($i<$num){
            array_push($users, self::generateUser());
            $i++;
        }
        return $users;
    }
}
