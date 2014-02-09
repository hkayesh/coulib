<?php
error_reporting(0);
session_start();
if (isset($_POST['submit'])) {
    include_once '../include/connectDB.php';
    $username = mysql_real_escape_string($_POST['username']);
    $password = mysql_real_escape_string($_POST['password']);


    $query = "SELECT * FROM `user` WHERE `username`='$username' and `password`= '$password'";
    $res = db_uery($query);

    if (mysql_num_rows($res) == 1) {
        session_destroy();
        session_start();
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['Session_token'] = "2013";
        header('Location: admin_home.php');
    } else {
        $msg = 'Invalid Username of password';
    }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <link rel="stylesheet" type="text/css" href="admin.css" />

    </head>
    <body>   


        <div id="main_content"> 

            <div class="admin_login">

                <div class="left_box">
                    <form  method="post" onsubmit="">
                        <div class="center_left_box">
                            <div class="box_title"><span>Admin</span> login</div>                    
<?php
if ($msg != null || $msg != "") {
    echo"<span style=\"color: red; text-align: center; \">$msg </span>";
}
?>
                            <div class="form">
                                <div class="form_row"><label class="left">Username: </label><input type="text" class="form_input" name="username"/></div> 
                                <div class="form_row"><label class="left">Password: </label><input type="password" class="form_input" name="password"/></div>                    
                                <div style="float:right; padding:10px 25px 0 0;">
                                    <input type="submit" name="submit" value="Login" />
                                </div>

                            </div>
                    </form>

                </div>
                <div class="bottom_left_box">
                </div>
            </div> 
        </div>            
    </body>