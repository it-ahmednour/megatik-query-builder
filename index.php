<?php define('DIRECT', TRUE);
require('system/autoload.php');
echo '<pre>';
###############################################################################
### Insert Query ###
echo '<h2>Insert Query</h2>';
$data = array (
    "fullname" => 'أحمد نور',
    "username" => 'Ahmed Nour2',
    "password" => md5('01100809215'),
    "email" => 'it2.nour@mail.com',
    "mobile" => '01148740661',
    "notes" => "<h1>My Notes ' ee</h1>",
    "active" => 1,
    "created_at" => time(),
    "updated_at" => NULL,
);
$insert['cmd'] = 'DB::table("users")->insert($data)->run();';
$insert['res'] = DB::table("users")->insert($data)->run();
if(!$insert['res']){
    $insert['res'] = DB::get_error();
}
print_r($insert);
###############################################################################
### Update Query ###
echo '<h2>Update Query</h2>';
$data = array (
    "username" => "Abo Malak",
    "email" => "it.ahmed@outlook.com",
    "updated_at" => time(),
);
$update['cmd'] = 'DB::table("users")->update($data)->where("id = 1")->run();';
$update['res'] = DB::table("users")->update($data)->where("id = 1")->run();
print_r($update);
###############################################################################
### Delete Query ###
echo '<h2>Delete Query</h2>';
$delete['cmd'] = 'DB::table("users")->delete()->where("id = 1")->sql();';
$delete['res'] = DB::table("users")->delete()->where("id = 1")->run();
print_r($delete);
###############################################################################
### Select Query ###
echo '<h2>Select Query</h2>';
$select[] = DB::table('users')->select()->sql();
$select[] = DB::table('users')->select(['id','username'])->sql();
$select[] = DB::table('users')->select(['id','username'])->where(["id = 1","name = 'ahmed' OR name = 'dd'",],"AND")->sql();
$select[] = DB::table('users AS u')->select()->join('posts AS p', 'u.id', 'p.uid')->sql();



$select[] = DB::table('users')->select()->getall();
$select[] = DB::table('users')->select(['id','username'])->get();
$select[] = DB::table('users')->select(['id','username'])->where(["id = 1","name = 'ahmed' OR name = 'dd'",],"AND")->get();
$select[] = DB::table('users AS u')->select()->join('posts AS p', 'u.id', 'p.uid')->get();
print_r($select);

















// $users = DB::table('users')->select(['id ','username','email'])->get();
// $users = DB::table('users')->min('id','minid')->get();

// print_r($users);

$test1[] = isSelect('MAX','id','mid');
$test1[] = isIm(['id','username','password']);

print_r($test1);

function isSelect($sql, $column, $name){
    return "{$sql}({$column})" . (!is_null($name) ? " AS {$name}" : '');
}

function isIm($column){
    return is_array($column) ? implode(', ', $column) : $column;
}