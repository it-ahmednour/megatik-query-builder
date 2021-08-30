<?php define('DIRECT', TRUE);
require('system/autoload.php');

// $users = DB::table('users')->select(['id ','username','email'])->get();
$users = DB::table('users')->min('id','minid')->get();
echo '<pre>';
print_r($users);

$test[] = isSelect('MAX','id','mid');
$test[] = isIm(['id','username','password']);

print_r($test);

function isSelect($sql, $column, $name){
    return "{$sql}({$column})" . (!is_null($name) ? " AS {$name}" : '');
}

function isIm($column){
    return is_array($column) ? implode(', ', $column) : $column;
}