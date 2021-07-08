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
$fname_err = $lname_err = $email_err = $pswd_err = $pswd2_err = '';
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST["fname"])){  
        $fname_err = "Please Enter First Name";  
    }  
    else{  
        if(!preg_match("/^[a-zA-Z ]*$/", $_POST["fname"])){  
            $fname_err = "Only Letters and whitespace allowed";  
        }  
    } 
    if(empty($_POST["fname"])){  
        $lname_err = '';  
    }  
    else{  
        if(!preg_match("/^[a-zA-Z ]*$/", $_POST["lname"])){  
            $lname_err = "Only Letters and whitespace allowed";  
        }  
    } 
    if(empty($_POST["email"])){  
        $email_err = "Please Enter Email";  
    }  
    else{  
        if(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){  
            $email_err = "Invalid Email format";  
        }  
    }
    if($email_err == ''){
        for ($i=0; $i < count($GLOBALS["data"]); $i++) { 
            if($_POST["email"]==$GLOBALS["data"][$i]['email']){
                $email_err = "Email already in use";
            }
        } 
    }  
    if(empty($_POST["pass"])){
        $pswd_err = "Please enter a password";  
    }
    else{
        if (strlen($_POST["pass"]) <= '8' || strlen($_POST["pass"]) > 16) {
            $pswd_err = "Your Password Must Contain between 8 to 16 Characters!";
        }
        elseif(!preg_match("#[0-9]+#",$_POST["pass"])) {
            $pswd_err = "Your Password Must Contain At Least 1 Number!";
        }
        elseif(!preg_match("#[a-z]+#",$_POST["pass"])) {
            $pswd_err = "Your Password Must Contain At Least 1 Lowercase Letter!";
        }
        elseif(!($_POST["pass"] == $_POST["pass2"])){
            $pswd2_err = "Please enter the same password";
        }
    }
    if($fname_err == '' && $lname_err == '' && $email_err == '' && $pswd_err == '' && $pswd2_err == ''){

        $fname = trim($_POST["fname"]);
        $lname = trim($_POST["lname"]);
        $email = trim($_POST["email"]);
        $password = $_POST["pass"];

        // Create connection
        global $servername, $dbusername, $dbpassword, $dbname;
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO users (email, fname, lname, password)
        VALUES ('$email','$fname', '$lname', '$password')";

        if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
        $_SESSION['username'] = $_POST["mail"];
        $_SESSION['fname'] = $GLOBALS['data'][$i]['fname'];
        header("Location: dashboard.php");
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
            <p>This is a basic implementation of login, logout & different levels of authorization. Creating an account is absolutely free of cost!</p>
            <h2>Create an account</h3> 
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <div class="row">
                    <div class="column-15">
                        <label for="name1">First Name</label>
                    </div>
                    <div class="column-45">
                        <input id="name1" type="text" name="fname" placeholder="Your first name">
                    </div>
                    <div class="column-40">
                        <a class="err"><?php echo $fname_err; ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="column-15">
                        <label for="name2">Last Name</label>
                    </div>
                    <div class="column-45">
                        <input id="name2" type="text" name="lname" placeholder="Your last name">
                    </div>
                    <div class="column-40">
                        <a class="err"><?php echo $lname_err; ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="column-15">
                        <label for="email">Email</label>
                    </div>
                    <div class="column-45">
                        <input id="email" type="email" name="email" placeholder="Enter a valid email">
                    </div>
                    <div class="column-40">
                        <a class="err"><?php echo $email_err; ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="column-15">
                        <label for="pwd">Password</label>
                    </div>
                    <div class="column-45">
                        <input id="pwd" type="password" name="pass" placeholder="Create a password">
                    </div>
                    <div class="column-40">
                        <a class="err"><?php echo $pswd_err; ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="column-15">
                        <label for="pwd2">Re-enter Password</label>
                    </div>
                    <div class="column-45">
                        <input id="pwd2" type="password" name="pass2" placeholder="Confirm password">
                    </div>
                    <div class="column-40">
                        <a class="err"><?php echo $pswd2_err; ?></a>
                    </div>
                </div>
                <div class="row">
                    <input id="sbutton" name="submit" type="submit" value="Create account">
                </div>
            </form>
            <h2 id="qsin">Already have an account?  <a id="link" href="login.php"><u>Sign-in</u></a></h2>            
        </div> 
    </div>  
</body>
</html>