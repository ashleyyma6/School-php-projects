<?php
function simple_substitution_encrypt($input_string, $key)
{
    // key must be 26 characters no repeat
    $key_order = get_key_order($key);
    $input_arr = str_to_pos($input_string);
    $length = count($input_arr);
    $encrypted = "";
    for($i=0;$i<$length;$i++){
        $encrypted.=$key_order[$input_arr[$i]];
    }
    print($encrypted);
}

function get_key_order($key){
    $key = strtolower($key);
    $key = preg_replace('/\s+/', '', $key);
    echo($key."<br>");
    if(strlen($key)<1){
        echo 'invalid key';
        return [];
    }else{
        $key_table = str_split($key);
        return $key_table;
    }

}

function str_to_pos($str){
    $alphabet = 'abcdefghijklmnopqrstuvwxyz';
    $input_arr = str_split($str);
    $length = count($input_arr);
    for($i=0;$i<$length;$i++){
        $input_arr[$i]=strpos($alphabet,$input_arr[$i]);
        echo  $input_arr[$i];
    }
    return $input_arr;
}

simple_substitution_encrypt('abcdef','fedcab');

?>