
<?php
error_reporting(0);
session_start();
if (!($_SESSION['Session_token'] == "2013") && !isset($_SESSION['username'])) {
    header('Location: login.php');
}
echo $_SESSION['Session_token'] == "2013"."&&&".$_SESSION['username'] ;
include '../include/connectDB.php';
$edition_array = array('1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '13th');

if (isset($_POST['submit'])) {
    $action = $_GET['action'];
    if ($action == 'CA') {
        $area_name = mysql_real_escape_string($_POST['area_name']);
        $sql = "Insert into area  (area_name, area_book_counter, ar_insert_date, ar_last_update) values ('$area_name', 0, NOW(), NOW())";
        if (mysql_query($sql)) {
            $msg = "Area added successfully!";
        } else {
            $msg = "Failed to add Area!";
        }
    } else if ($action == 'a_edit') {
        $area_name = mysql_real_escape_string($_POST['area_name']);
        $area_id = mysql_real_escape_string($_GET['aid']);
        $sql = "UPDATE area SET area_name = '$area_name', ar_last_update=NOW() where area_id = '$area_id'";
        if (mysql_query($sql)) {
            $msg = "Area updated Successfully!";
        } else {
            $msg = "Failed to update area!";
        }
    } else if ($_GET['action'] == 'CB') {
        $book_title = mysql_real_escape_string($_POST['book_title']);
        $author = mysql_real_escape_string($_POST['author']);
        $area_id = mysql_real_escape_string($_POST['area_id']);
        $edition = mysql_real_escape_string($_POST['edition']);
        $isbn = mysql_real_escape_string($_POST['isbn']);
        $number_of_copy = mysql_real_escape_string($_POST['number_of_copy']);
        $status = mysql_real_escape_string($_POST['status']);
        $sql = "SELECT area_book_counter FROM area WHERE area_id = '$area_id'";
        $rs = mysql_query($sql);
        if ($rs) {
            $row = mysql_fetch_array($rs);
            $area_book_counter = $row['area_book_counter'] + 1;
            $sql = "UPDATE area SET area_book_counter = $area_book_counter where area_id = $area_id";
            if (mysql_query($sql)) {
                $sql = "INSERT INTO book (title, author, edition, isbn, number_of_copy, status, area_counter, fk_area_id, bk_insert_date, bk_last_update) VALUES ('$book_title', '$author', '$edition', '$isbn', '$number_of_copy', '$status', '$area_book_counter', '$area_id', NOW(), NOW())";
                if (mysql_query($sql)) {
                    $msg = "Book added Successfully!";
                } else {
                    $area_book_counter = $area_book_counter - 1;
                    $sql = "UPDATE area SET area_book_counter = '$area_book_counter' where area_id = '$area_id'";
                    mysql_query($sql);
                    $msg = "Failed to add book!";
                }
            } else {
                $msg = "Failed to add book!";
            }
        } else {
            $msg = "Failed to add book!";
        }
    } else if ($action == 'edit') {
        $book_id_number = mysql_real_escape_string($_GET['bid']);
        $book_title = mysql_real_escape_string($_POST['book_title']);
        $author = mysql_real_escape_string($_POST['author']);
        $area_id = mysql_real_escape_string($_POST['area_id']);
        $edition = mysql_real_escape_string($_POST['edition']);
        $isbn = mysql_real_escape_string($_POST['isbn']);
        $number_of_copy = mysql_real_escape_string($_POST['number_of_copy']);
        $status = mysql_real_escape_string($_POST['status']);

        $sql = "UPDATE book SET title = '$book_title', author='$author', edition = '$edition', isbn = '$isbn', number_of_copy = '$number_of_copy', fk_area_id = '$area_id', status = '$status' where book_id = '$book_id_number'";
        if (mysql_query($sql)) {
            $msg = "Book updated successfully";
        } else {
            $msg = "Failed to update book";
        }
    } else if ($action == 'AO') {
        $username = mysql_real_escape_string($_POST['username']);
        $password = mysql_real_escape_string($_POST['password']);

        $sql = "UPDATE user SET username='$username', password = '$password'";
        if (mysql_query($sql)) {
            $_SESSION['username'] = $username;
            $msg = "Information Updated successfully";
        } else {
            $msg = "Failed to update information";
        }
    }
} else {
    $msg = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Library Management | Stat. COU</title>

        <link href="../style/style.css" rel="stylesheet" type="text/css" />
        <link rel="icon" type="image/png" href="../images/fav.ico" />

        <script type="text/javascript" src="../js/external/mootools.js"></script>
        <script type="text/javascript" src="../js/dg-filter.js"></script>
    </head>
    <body>
        <div id="container" align="center">
            <div id="header">
            </div>
        </div>

        <div>
            <fieldset id="fieldset_style">
                <?php
                if ($_GET['action'] == 'CB') {
                    ?>
                    <div id="table_header_style">
                        <table border="0" style="width: 100%; height: 100%;font-size: 17px">
                            <tr align="center">
                                <td style="text-align: left; width: 22%; font-size: 20px; color: #FAF6F6; font-family: cursive">Create New Book</td>
                            </tr>
                        </table>
                    </div>
                    <table border="0" width="100%" cellpadding="2px" style="padding-left: 60px">
                        <tr>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LB'; ?>">List Book</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CB'; ?>">Create Book</a></td>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LA'; ?>">List Area</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CA'; ?>">Create Area</a></td>
                            <td width="56%" style="text-align: right"><a href="../index.php" target="_blank">User View</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=AO'; ?>">Admin Option</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LO'; ?>">Logout</a></td>
                        </tr>
                    </table>
                    <form method="post" onsubmit="">
                        <table id="info_table" border="0" align="left" width= 50%" cellpadding="5px" cellspacing="0px" style="padding-left: 60px">
                            <tr id="table_row_odd">
                                <td  style="text-align: right; vertical-align: central" width="35%">Book Title&nbsp;:</td>
                                <td><input type="text" name="book_title" style="width: 300px; height: 20px;" maxlength="300"/></td>
                            </tr>
                            <tr id="table_row_even">
                                <td  style="text-align: right; vertical-align: central" width="25%">Author&nbsp;:</td>
                                <td><input type="text" name="author" style="width: 300px; height: 20px;" maxlength="300"/></td>
                            </tr>
                            <tr id="table_row_odd">
                                <td  style="text-align: right; vertical-align: central" width="25%">Area&nbsp;:</td>
                                <td>
                                    <select name="area_id" style="width: 200px;">
                                        <?php
                                        $sql = "SELECT * FROM area";

                                        $res = mysql_query($sql);
                                        while ($row = mysql_fetch_array($res)) {
                                            $area_id = $row['area_id'];
                                            $area_name = $row['area_name'];
                                            echo "<option value=$area_id>$area_name</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="table_row_even">
                                <td  style="text-align: right; vertical-align: central" width="25%">Edition&nbsp;:</td>
                                <td>
                                    <select name="edition" style="width: 100px; text-align: right;">
                                        <?php
                                        for ($i = 0; $i < count($edition_array); $i++) {
                                            echo "<option value=" . $edition_array[$i] . ">" . $edition_array[$i] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="table_row_odd">
                                <td  style="text-align: right; vertical-align: central" width="25%">ISBN Number&nbsp;:</td>
                                <td><input type="text" name="isbn" style="width: 200px; height: 20px;" maxlength="100"/></td>
                            </tr>
                            <tr id="table_row_even">
                                <td  style="text-align: right; vertical-align: central" width="25%">Number of copy&nbsp;:</td>
                                <td><input type="text" name="number_of_copy" style="width: 100px; height: 20px;" maxlength="30"/></td>
                            </tr>
                            <tr id="table_row_odd">
                                <td  style="text-align: right; vertical-align: central" width="25%">Status&nbsp;:</td>
                                <td>
                                    <select name="status">
                                        <option value="Available" selected="selected">Available</option>
                                        <option value="NotAvailable">Not Available</option>
                                    </select>
                                </td>
                            </tr>
                            <?php
                            if ($msg != "") {
                                echo "<tr id=\"table_row_even\" height=\"15px\">";
                                echo "<td colspan=\"2\" align=\"center\">";
                                echo $msg;
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr id="table_row_even">
                                <td colspan="2" align="center">
                                    <input type="submit" name="submit" value="Save">
                                </td>
                            </tr>
                        </table>
                    </form>
                    <?php
                } else if ($_GET['action'] == 'LA') {
                    ?> 
                    <div id="table_header_style">
                        <table border="0" style="width: 100%; height: 100%;font-size: 17px">
                            <tr align="center">
                                <td style="text-align: left; width: 22%; font-size: 20px; color: #FAF6F6; font-family: cursive">List of Area</td>
                                <td style="text-align: right;">  Search:<input type="text" id="search_box_style" name ="search_box_style" /></td>
                            </tr>
                        </table>
                    </div>
                    <table border="0" width="100%" cellpadding="2px" style="padding-left: 60px">
                        <tr>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LB'; ?>">List Book</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CB'; ?>">Create Book</a></td>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LA'; ?>">List Area</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CA'; ?>">Create Area</a></td>
                            <td width="56%" style="text-align: right"><a href="../index.php" target="_blank">User View</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=AO'; ?>">Admin Option</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LO'; ?>">Logout</a></td>
                        </tr>
                    </table>
                    <div style="height: 350px; overflow-y: scroll;">
                        <table id="info_table" border="0" align="center" width= 90%" cellpadding="5px" cellspacing="0px">
                            <tr id="table_row_odd">
                                <td width="7%" style="text-align: center"><b>Serial</b></td>
                                <td width="33%"><b>Area Name</b></td>
                                <td width="25%" style="text-align: center"><b>Books in Area</b></td>
                                <td width="20%"><b>Insert Date</b></td>
                                <td style="text-align: center"><b>Option</b></td>
                            </tr>

                            <?php
                            $sql = "SELECT * FROM area";

                            $res = mysql_query($sql);

                            $flag = true;
                            $serial = 1;
                            while ($row = mysql_fetch_array($res)) {
                                $area_id = $row['area_id'];
                                $area_name = $row['area_name'];
                                $area_number_of_books = $row['area_book_counter'];
                                $area_insert_date = $row['ar_insert_date'];

                                if ($flag)
                                    $row_style = "table_row_even";
                                else
                                    $row_style = "table_row_odd";
                                ?>
                                <tr id=<?php echo $row_style; ?>>
                                    <td style="text-align: center"><?php echo $serial; ?></td>
                                    <td><?php echo $area_name; ?></td>
                                    <td style="text-align: center"><?php echo $area_number_of_books; ?></td>
                                    <td><?php echo $area_insert_date; ?></td>
                                    <td style="text-align: center"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=a_edit&aid=' . $area_id; ?>">Edit</a></td>
                                </tr>

                                <?php
                                $flag = !$flag;
                                $serial++;
                            }
                            ?>            
                        </table>
                    </div>
                    <script type="text/javascript">
                        //initSortTable('myTable',Array('S','N','S','N','S'));

                        var filter = new DG.Filter({
                            filterField : $('search_box_style'),
                            filterEl : $('info_table')
                        });
                    </script>

                    <?php
                } else if ($_GET['action'] == 'CA') {
                    ?>   
                    <div id="table_header_style">
                        <table border="0" style="width: 100%; height: 100%;font-size: 17px">
                            <tr align="center">
                                <td style="text-align: left; width: 22%; font-size: 20px; color: #FAF6F6; font-family: cursive">Create New Area</td>
                            </tr>
                        </table>
                    </div>
                    <table border="0" width="100%" cellpadding="2px" style="padding-left: 60px">
                        <tr>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LB'; ?>">List Book</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CB'; ?>">Create Book</a></td>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LA'; ?>">List Area</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CA'; ?>">Create Area</a></td>
                            <td width="56%" style="text-align: right"><a href="../index.php" target="_blank">User View</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=AO'; ?>">Admin Option</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LO'; ?>">Logout</a></td>
                        </tr>
                    </table>
                    <form onsubmit="" method="post">
                        <table id="info_table" border="0" align="left" width= 50%" cellpadding="5px" cellspacing="0px" style="padding-left: 60px">
                            <tr id="table_row_odd">
                                <td  style="text-align: center; vertical-align: central" width="25%">Area Name&nbsp;:</td>
                                <td><input type="text" name="area_name" style="width: 300px; height: 20px;" maxlength="300"></td>
                            </tr>
                            <?php
                            if ($msg != "") {
                                echo "<tr id=\"table_row_even\" height=\"15px\">";
                                echo "<td colspan=\"2\" align=\"center\">";
                                echo $msg;
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr id="table_row_even">
                                <td colspan="2" align="center">
                                    <input type="submit" name="submit" value="Save">
                                </td>
                            </tr>
                        </table>
                    </form>

                    <?php
                } else if ($_GET['action'] == 'edit') {
                    $book_id = $_GET['bid'];
                    $area_id = $_GET['aid'];

                    $sql = "SELECT * FROM book, area where book_id = '$book_id' and area_id = '$area_id'";

                    $res = mysql_query($sql);
                    $row = mysql_fetch_array($res);

                    $title = $row['title'];
                    $author = $row['author'];
                    $area = $row['area_name'];
                    $edition = $row['edition'];
                    $isbn = $row['isbn'];
                    $status = $row['status'];
                    $number_of_copy = $row['number_of_copy'];
                    ?>
                    <div id="table_header_style">
                        <table border="0" style="width: 100%; height: 100%;font-size: 17px">
                            <tr align="center">
                                <td style="text-align: left; width: 22%; font-size: 20px; color: #FAF6F6; font-family: cursive">Edit Book</td>
                            </tr>
                        </table>
                    </div>
                    <table border="0" width="100%" cellpadding="2px" style="padding-left: 60px">
                        <tr>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LB'; ?>">List Book</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CB'; ?>">Create Book</a></td>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LA'; ?>">List Area</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CA'; ?>">Create Area</a></td>
                            <td width="56%" style="text-align: right"><a href="../index.php" target="_blank">User View</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=AO'; ?>">Admin Option</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LO'; ?>">Logout</a></td>
                        </tr>
                    </table>
                    <form method="post" onsubmit="">
                        <table id="info_table" border="0" align="left" width= 50%" cellpadding="5px" cellspacing="0px" style="padding-left: 60px">
                            <tr id="table_row_odd">
                                <td  style="text-align: right; vertical-align: central" width="35%">Book Title&nbsp;:</td>
                                <td><input type="text" name="book_title" style="width: 300px; height: 20px;" maxlength="300" value="<?php echo $title; ?>"/></td>
                            </tr>
                            <tr id="table_row_even">
                                <td  style="text-align: right; vertical-align: central" width="25%">Author&nbsp;:</td>
                                <td><input type="text" name="author" style="width: 300px; height: 20px;" maxlength="300" value="<?php echo $author; ?>"/></td>
                            </tr>
                            <tr id="table_row_odd">
                                <td  style="text-align: right; vertical-align: central" width="25%">Area&nbsp;:</td>
                                <td>
                                    <select name="area_id">
                                        <?php
                                        $sql = "SELECT * FROM area";

                                        $res = mysql_query($sql);
                                        while ($row = mysql_fetch_array($res)) {
                                            $list_area_id = $row['area_id'];
                                            $list_area_name = $row['area_name'];
                                            if ($area == $list_area_name) {
                                                echo "<option value=$list_area_id selected>$list_area_name</option>";
                                            } else {
                                                echo "<option value=$list_area_id>$list_area_name</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="table_row_even">
                                <td  style="text-align: right; vertical-align: central" width="25%">Edition&nbsp;:</td>
                                <td>
                                    <select name="edition" style="width: 100px; text-align: right;">
                                        <?php
                                        for ($i = 0; $i < count($edition_array); $i++) {
                                            if ($edition == $edition_array[$i]) {
                                                echo "<option value=" . $edition_array[$i] . " selected>" . $edition_array[$i] . "</option>";
                                            } else {
                                                echo "<option value=" . $edition_array[$i] . " >" . $edition_array[$i] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="table_row_odd">
                                <td  style="text-align: right; vertical-align: central" width="25%">ISBN Number&nbsp;:</td>
                                <td><input type="text" name="isbn" style="width: 200px; height: 20px;" maxlength="100" value="<?php echo $isbn; ?>"/></td>
                            </tr>
                            <tr id="table_row_even">
                                <td  style="text-align: right; vertical-align: central" width="25%">Number of copy&nbsp;:</td>
                                <td><input type="text" name="number_of_copy" style="width: 100px; height: 20px;" maxlength="30" value="<?php echo $number_of_copy; ?>"/></td>
                            </tr>
                            <tr id="table_row_odd">
                                <td  style="text-align: right; vertical-align: central" width="25%">Status&nbsp;:</td>
                                <td>
                                    <select name="status">
                                        <?php
                                        if ($status == "Available") {
                                            ?>
                                            <option value="Available" selected>Available</option>
                                            <option value="Not Available" >Not Available</option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="Available">Available</option>
                                            <option value="Not Available" selected>Not Available</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <?php
                            if ($msg != "") {
                                echo "<tr id=\"table_row_even\" height=\"15px\">";
                                echo "<td colspan=\"2\" align=\"center\">";
                                echo $msg;
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr id="table_row_even">
                                <td colspan="2" align="center">
                                    <input type="submit" name="submit" value="Save">
                                </td>
                            </tr>
                        </table>
                    </form>   


                    <?php
                } else if ($_GET['action'] == 'a_edit') {
                    ?>
                    <div id="table_header_style">
                        <table border="0" style="width: 100%; height: 100%;font-size: 17px">
                            <tr align="center">
                                <td style="text-align: left; width: 22%; font-size: 20px; color: #FAF6F6; font-family: cursive">Edit Area</td>
                            </tr>
                        </table>
                    </div>
                    <table border="0" width="100%" cellpadding="2px" style="padding-left: 60px">
                        <tr>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LB'; ?>">List Book</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CB'; ?>">Create Book</a></td>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LA'; ?>">List Area</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CA'; ?>">Create Area</a></td>
                            <td width="56%" style="text-align: right"><a href="../index.php" target="_blank">User View</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=AO'; ?>">Admin Option</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LO'; ?>">Logout</a></td>
                         </tr>
                    </table>
                    <?php
                    $area_id = $_GET[aid];
                    $sql = "select area_name from area where area_id='$area_id'";
                    $rs = mysql_query($sql);
                    $row = mysql_fetch_array($rs);
                    $area_name = $row['area_name'];
                    ?>
                    <form onsubmit="" method="post">
                        <table id="info_table" border="0" align="left" width= 50%" cellpadding="5px" cellspacing="0px" style="padding-left: 60px">
                            <tr id="table_row_odd">
                                <td  style="text-align: center; vertical-align: central" width="25%">Area Name&nbsp;:</td>
                                <td><input type="text" name="area_name" value="<?php echo $area_name; ?>" style="width: 300px; height: 20px;" maxlength="300"></td>
                            </tr>
                            <?php
                            if ($msg != "") {
                                echo "<tr id=\"table_row_even\">";
                                echo "<td colspan=\"2\" align=\"center\">";
                                echo $msg;
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr id="table_row_even">
                                <td colspan="2" align="center">
                                    <input type="submit" name="submit" value="Save">
                                </td>
                            </tr>
                        </table>
                    </form>
                    <?php
                } else if ($_GET['action'] == 'LO') {
                    session_start();
                    session_destroy();
                    header('Location: login.php');
                } else if ($_GET['action'] == 'AO') {
                    $username = $_SESSION['username'];
                    $sql = "SELECT * FROM user WHERE username='$username'";
                    $res = mysql_query($sql);
                    $row = mysql_fetch_array($res);
                    $password = $row['password'];
                    ?>
                    <div id="table_header_style">
                        <table border="0" style="width: 100%; height: 100%;font-size: 17px">
                            <tr align="center">
                                <td style="text-align: left; width: 22%; font-size: 20px; color: #FAF6F6; font-family: cursive">Admin Option</td>
                            </tr>
                        </table>
                    </div>
                    <table border="0" width="100%" cellpadding="2px" style="padding-left: 60px">
                        <tr>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LB'; ?>">List Book</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CB'; ?>">Create Book</a></td>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LA'; ?>">List Area</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CA'; ?>">Create Area</a></td>
                            <td width="56%" style="text-align: right"><a href="../index.php" target="_blank">User View</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=AO'; ?>">Admin Option</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LO'; ?>">Logout</a></td>
                        </tr>
                    </table>
                    <form method="post" onsubmit="">
                        <table id="info_table" border="0" align="left" width= 50%" cellpadding="5px" cellspacing="0px" style="padding-left: 60px">
                            <tr id="table_row_odd">
                                <td  style="text-align: right; vertical-align: central" width="35%">Username&nbsp;:</td>
                                <td><input type="text" name="username" style="width: 300px; height: 20px;" maxlength="50" value="<?php echo $username; ?>"/></td>
                            </tr>
                            <tr id="table_row_even">
                                <td  style="text-align: right; vertical-align: central" width="25%">Password&nbsp;:</td>
                                <td><input type="text" name="password" style="width: 300px; height: 20px;" maxlength="50" value="<?php echo $password; ?>"/></td>
                            </tr>
                            <?php
                            if ($msg != "") {
                                echo "<tr id=\"table_row_odd\">";
                                echo "<td colspan=\"2\" align=\"center\">";
                                echo $msg;
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr id="table_row_odd">
                                <td colspan="2" align="center">
                                    <input type="submit" name="submit" value="Save">
                                </td>
                            </tr>
                        </table>
                    </form>

                    <?php
                } else {
                    ?>
                    <div id="table_header_style">
                        <table border="0" style="width: 100%; height: 100%;font-size: 17px">
                            <tr align="center">
                                <td style="text-align: left; width: 22%; font-size: 20px; color: #FAF6F6; font-family: cursive">List of Books</td>
                                <td style="text-align: right;">  Search:<input type="text" id="search_box_style" name ="search_box_style" /></td>
                                <!--<td style="text-align: left; width: 10%; padding-left: 10px;"><input type="submit" name ="Submit" value="Search"/></td>-->
                            </tr>
                        </table>
                    </div>
                    <table border="0" width="100%" cellpadding="2px" style="padding-left: 60px">
                        <tr>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LB'; ?>">List Book</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CB'; ?>">Create Book</a></td>
                            <td width="10%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LA'; ?>">List Area</a></td>
                            <td width="12%"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=CA'; ?>">Create Area</a></td>
                            <td width="56%" style="text-align: right"><a href="../index.php" target="_blank">User View</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=AO'; ?>">Admin Option</a>&nbsp;|&nbsp;<a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=LO'; ?>">Logout</a></td>
                        </tr>
                    </table>

                    <div style="height: 400px; overflow-y: scroll;">
                        <table id="info_table" border="0" align="center" width= 99%" cellpadding="5px" cellspacing="0px">
                            <thead>
                                <tr id="table_row_odd">
                                    <th width="8%"><b>Book ID</b></th>
                                    <th width="25%"><b>Title</b></th>
                                    <th width="15%"><b>Author</b></th>
                                    <th width="15%"><b>Area</b></th>
                                    <th width="5%"style="text-align: center"><b>Edition</b></th>
                                    <th width="15%" style="text-align: center"><b>ISBN</b></th>
                                    <th width="12%"><b>Status</b></th>
                                    <th width="5%" style="text-align: center"><b>Copy</b></th>
                                    <th style="text-align: center"><b>Option</b></th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $sql = "SELECT * FROM book, area where fk_area_id = area_id";

                                $res = mysql_query($sql);

                                $flag = true;

                                while ($row = mysql_fetch_array($res)) {
                                    $book_id_number = $row['book_id'] . "-" . $row['fk_area_id'] . "-" . $row['area_counter'];
                                    $title = $row['title'];
                                    $author = $row['author'];
                                    $area = $row['area_name'];
                                    $edition = $row['edition'];
                                    $isbn = $row['isbn'];
                                    $status = $row['status'];
                                    $number_of_copy = $row['number_of_copy'];

                                    if ($flag)
                                        $row_style = "table_row_even";
                                    else
                                        $row_style = "table_row_odd";
                                    ?>
                                    <tr id="<?php echo $row_style; ?>">
                                        <td><?php echo $book_id_number; ?></td>
                                        <td><?php echo $title; ?></td>
                                        <td><?php echo $author; ?></td>
                                        <td><?php echo $area; ?></td>
                                        <td style="text-align: center"><?php echo $edition; ?></td>
                                        <td style="text-align: center"><?php echo $isbn; ?></td>
                                        <td><?php echo $status; ?></td>
                                        <td style="text-align: center"><?php echo $number_of_copy; ?></td>
                                        <td style="text-align: center"><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?action=edit&bid=' . $row['book_id'] . '&aid=' . $row['fk_area_id']; ?>">Edit</a></td>
                                    </tr>

                                    <?php
                                    $flag = !$flag;
                                }
                                ?>            
                            </tbody>
                        </table>
                    </div>

                    <script type="text/javascript">
                        //initSortTable('myTable',Array('S','N','S','N','S'));

                        var filter = new DG.Filter({
                            filterField : $('search_box_style'),
                            filterEl : $('info_table')
                        });
                    </script>

                    <?php
                }
                ?>

            </fieldset>
        </div>
    </body>
</html>

