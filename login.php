<?php 
session_start();
include 'conn.php';
function callOnce(){
    static $called = false;
    if($called){
        return;
    }
    $GLOBALS['data'] = fetchUsers();
}
callOnce();
$error_message = '';
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $exists = false;
    for($i = 0; $i < count($GLOBALS['data']);$i++){
        if($_POST['mail'] == $GLOBALS['data'][$i]['email']){
            $exists = true;
            break;
        }
    }
    if($exists){
        if($_POST['pass'] != $GLOBALS['data'][$i]['password']){
            $error_message = 'Invalid email or password';
        }
    }else{
        $error_message = 'Invalid email or password';
    }
    if($error_message == ''){
        $_SESSION['username'] = $_POST["mail"];
        $_SESSION['fname'] = $GLOBALS['data'][$i]['fname'];
        if($GLOBALS['data'][$i]['auth'] == 0){
            header("Location: dashboard.php");
        }
        else{
            header("Location: admin.php");
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Welcome to LoginFlow</title>
</head>
<body>
    <div class="container">
        <div class="content">
            <h1>Welcome To LoginFlow</h1>
            <p>This is a basic implementation of login, logout & different levels of authorization. Login to view your dashboard.</p>
            <h2>Login</h3> 
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <div class="row">
                    <div class="column-15">
                        <label for="email">Email</label>
                    </div>
                    <div class="column-45">
                        <input id="email" type="email" name="mail" placeholder="Enter email">
                    </div>
                    <div class="column-40">
                        <a class="err"><?php echo $error_message; ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="column-15">
                        <label for="pwd">Password</label>
                    </div>
                    <div class="column-45">
                        <input id="pwd" type="password" name="pass" placeholder="Enter your password">
                    </div>
                </div>
                <div class="row">
                    <input id="sbutton" type="submit" value="Submit">
                </div>
            </form>  
            <h2 id="qsin">Don't have an account?  <a id="link" href="index.php"><u>Sign-up</u></a></h2>          
        </div> 
    </div>  
</body>
</html>