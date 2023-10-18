<?php

class ServiceJSON {
    public function send($code, $msg, $extra=null){
        header('Content-Type: application/json; charset=utf-8');
        $rtnArr = ["code: " => $code, "msg: " => $msg];
        if($extra != null){
            $rtnArr["return"] = [$extra];
            echo json_encode($rtnArr);
        }else{
            echo json_encode($rtnArr);
        }

        header("HTTP/1.1 $code $msg");  
    }
}
