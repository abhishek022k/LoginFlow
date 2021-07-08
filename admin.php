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
$adminMessage ='';
for($s = 0; $s < count($GLOBALS['data']);$s++){
    if($_SESSION['username'] == $GLOBALS['data'][$s]['email']){
        if($GLOBALS['data'][$s]['auth'] != 1){
            $adminMessage = 'WARNING: You have removed your admin authorization. You won\'t be able to access this page once you logout.';
        }
        break;
    }
}
if(isset($_POST['saveChanges'])){
    if(!empty($_POST['delete_list'])){
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $query3 = "DELETE from users where email IN(";
        $querymaker = [];
        for($k = 0; $k < count($_POST['delete_list']);$k++){
            array_push($querymaker,"'".$_POST['delete_list'][$k]."'");
        }
        $query3.= implode(',',$querymaker);
        $query3.=");";
        try{
            $result = $conn->query($query3);
        }catch(Exception $e){
            echo "delete failed : ".$e->getMessage();
            $conn->close();
            exit;
        }
        $conn->close();
    }
    if(!empty($_POST['admin_list'])){
        $makeAdmin = $removeAdmin = [];
        for($j = 0; $j < count($_POST['admin_list']);){
            if(strcmp($_POST['admin_list'][$j],$_POST['admin_list'][$j+1])==0){
                if(!($_POST['admin_list'][$j+2] == 'yes')){
                    array_push($makeAdmin,$_POST['admin_list'][$j]);
                    $j+=2;
                }else{
                    $j += 3;
                }                
            }else{
                if($_POST['admin_list'][$j+1]=='yes'){
                    array_push($removeAdmin,$_POST['admin_list'][$j]);
                    $j+=2;
                }else{
                    $j+=1;
                }
            }
        }
        if(!empty($makeAdmin) || !empty($removeAdmin)){
            $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            if(!empty($makeAdmin)){
                $query1 = "UPDATE users SET auth = 1 where email in(";
                $querystr1 = [];
                for($k = 0; $k < count($makeAdmin);$k++){
                    array_push($querystr1,"'".$makeAdmin[$k]."'");
                }
                $query1 .= implode(',',$querystr1);
                $query1 .= ");";
                try{
                    $result = $conn->query($query1);
                }catch(Exception $e){
                    echo "makeadmin failed : ".$e->getMessage();
                    $conn->close();
                    exit;
                }
            }else if(!empty($removeAdmin)){
                $query2 = "UPDATE users SET auth = 0 where email in(";
                $querystr2 = [];
                for($k = 0; $k < count($removeAdmin);$k++){
                    array_push($querystr2,"'".$removeAdmin[$k]."'");
                }
                $query2 .= implode(',',$querystr2);
                $query2 .= ");";
                try{
                    $result = $conn->query($query2);
                }catch(Exception $e){
                    echo "removeadmin failed : ".$e->getMessage();
                    $conn->close();
                    exit;
                }
            }
            $conn->close();          
        }
        
    }
    if(!empty($_POST['delete_list']) || !empty($_POST['admin_list'])){
        unset($_POST);
        header("Location: admin.php");
        exit;  
    }
}elseif(isset($_POST['undoChanges'])){
    unset($_POST);
    header("Location: admin.php");
    exit;
}
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
            <h1>Welcome To Your Admin page, <?php echo $_SESSION['fname'];?></h1>
            <p>You can view the list of users who use LoginFlow here. Select the respective checkboxes to delete a user 
                or change their authorization. Click 'Save changes' to save changes made & 'Undo Changes' to reset.</p>
            <div>
                <form method="POST">
                    <table style="width : 100%">
                        <tr>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Auth level</th>
                            <th>Delete</th>
                            <th>Admin access</th>
                        </tr>
                        <?php
                        for ($i=0; $i < count($GLOBALS['data']); $i++) { 
                            echo "<tr>
                            <td>".$GLOBALS['data'][$i]['email']."</td>
                            <td>".$GLOBALS['data'][$i]['fname']."</td>
                            <td>".$GLOBALS['data'][$i]['lname']."</td>
                            <td>";
                            if($GLOBALS['data'][$i]['auth'] == 0){
                                echo "Normal</td><td>";
                            }else{
                                echo "Admin</td><td>";
                            }
                            echo "<input type='checkbox' name='delete_list[]' value='".$GLOBALS['data'][$i]['email']."'></td><td>
                            <input type='hidden' name='admin_list[]' value='".$GLOBALS['data'][$i]['email']."'>
                            <input type='checkbox' name='admin_list[]' value='".$GLOBALS['data'][$i]['email']."' ";
                            if($GLOBALS['data'][$i]['auth'] == 1){
                                echo "checked><input type='hidden' name='admin_list[]' value='yes'></td></tr>";
                            }else{
                                echo "></td></tr>";
                            }
                        }
                        ?>
                    </table>
                    <input id="sbutton" type="submit" value="Save Changes" name="saveChanges" style="margin: 10px 0px 10px 0px; font-size:large">
                    <input id="sbutton" type="submit" value="Undo Changes" name="undoChanges" style="margin: 10px 0px 10px 20px; font-size:large">
                </form>
            </div>
            <p class="err" style="font-size : medium;"><?php echo $adminMessage; ?></p>
        </div>
    </div>    
</body>
</html>