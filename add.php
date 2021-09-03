<?php define('DIRECT', TRUE);
require('system/autoload.php');
if(isset($_POST['submit'])){
    $data = array (
        "fullname" => $_POST['fullname'],
        "username" => $_POST['username'],
        "password" => md5($_POST['password']),
        "email" => $_POST['email'],
        "mobile" => $_POST['mobile'],
        "notes" => $_POST['notes'],
        "active" => 1,
        "created_at" => time(),
        "updated_at" => NULL,
    );
    
    $insert = DB::table('users')->insert($data)->save();
    if($insert){
        $msg =  "<div class='alert alert-success'>User Created Success With ID : " . DB::lastInsertId() ."</div>";
    }else{
        $msg =  "<div class='alert alert-danger'>" . DB::get_error() ."</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?=ROOT;?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=ROOT;?>/assets/css/all.min.css">
    <title>Add User</title>
</head>
<body>
    
    <div class="container-fluid">
        <div class="mx-auto" style="margin-top: 50px;width: 100%;max-width: 800px;">
            <h2 class="text-center">Add User</h2><br><br>
            <?php echo $msg ?? null; ?>
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" autocomplete="on">
                <input type="text" class="form-control" name="fullname" placeholder="FullName" value="احمد نور"><br>
                <input type="text" class="form-control" name="username" placeholder="Username" value="Ahmed Nour"><br>
                <input type="password" class="form-control" name="password" placeholder="Password" value="123456"><br>
                <input type="email" class="form-control" name="email" placeholder="Email" value="it.ahmednour@gmail.com"><br>
                <input type="tel" class="form-control" name="mobile" placeholder="mobile" value="0105050501"><br>
                <textarea name="notes" class="form-control" rows="3">Test The's Class Secure Input HTML</textarea><br>
                <button type="submit" name="submit" class="btn btn-primary">Add User</button>
            </form>
        </div>
    </div>

<script src="<?=ROOT;?>/assets/js/bootstrap.min.js"></script>
</body>
</html>