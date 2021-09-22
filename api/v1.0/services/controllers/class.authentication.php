<?php
class userAuth{
    
	function userPwdEncode($password){
        
        $pwd_encode = md5($password);
        return $pwd_encode;
        
        
    }
    
    function userPwdDecode($password){
        
        return $password;
        
    }
    
}
