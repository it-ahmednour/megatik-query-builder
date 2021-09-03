<?php define('DIRECT', TRUE);
require('system/autoload.php');
if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])){
        $data = [
            "username" => $_POST['username'],
            "password" => $_POST['password'],
        ];
        $auth = Auth::login($data);
        $msg = Auth::error();
        if($auth){
            header( "Refresh:1; url=index.php", true, 303);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <title>Login</title>
</head>
<body>
    <div class="container-fluid">
        <div class="mx-auto" style="margin-top: 50px;width: 100%;max-width: 360px;">
            <h2 class="text-center">Admin Login</h2><br><br>
            <?php echo $msg ?? null; ?>
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" autocomplete="on">
                <input type="text" class="form-control" name="username" placeholder="Username" value="Ahmed Nour"><br>
                <input type="password" class="form-control" name="password" placeholder="Password" value="01100809215"><br>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>