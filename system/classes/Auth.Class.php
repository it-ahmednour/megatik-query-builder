<?php
class Auth{
    public static $error_msg = 'ddd';
    public function login(Array $data){
        $user = DB::table('users')->select()->get();
    }
    public function check($type){
        if($type === 'admin'){
            return true;
        }else{
            return false;
        }
    }

    public function error(){
        return self::$error_msg;
    }
}