<?php

class FactoryPartida{
 
    static function createTablero($size, $numFlags){
        $tablero = array_fill(0,$size,0);

        while (0 < $numFlags) {
            $tablero[rand(0,$size-1)]=1;
            $numFlags--;
        }
        
        return $tablero;
    }
}
