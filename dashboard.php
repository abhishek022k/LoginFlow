<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();    
}
include 'conn.php';
function callOnce(){
    static $called = false;
    if($called){
        return;
    }
    $GLOBALS['data'] = fetchUsers();
}
callOnce();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="content">
            <a id="link" class = "logout"href="logout.php">Logout</a>
            <h1>Welcome To Your Dashboard, <?php echo $_SESSION['fname'];?></h1>
            <p>You can view the list of users who use LoginFlow here.</p>
            <div>
                <table style="width : 100%">
                    <tr>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Auth level</th>
                    </tr>
                    <?php
                    for ($i=0; $i < count($GLOBALS['data']); $i++) { 
                        echo "<tr>
                        <td>".$GLOBALS['data'][$i]['email']."</td>
                        <td>".$GLOBALS['data'][$i]['fname']."</td>
                        <td>".$GLOBALS['data'][$i]['lname']."</td>
                        <td>";
                        if($GLOBALS['data'][$i]['auth'] == 0){
                            echo "Normal</td></tr>";
                        }else{
                            echo "Admin</td></tr>";
                        }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>    
</body>
</html>