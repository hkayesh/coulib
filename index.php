<?php
    include_once 'include/header.php';
    $sql = "SELECT * FROM `book`,`area` where `fk_area_id` = `area_id` LIMIT 0, 30 ";
    
    $rs = db_uery($sql);
    
    while($row = mysql_fetch_array($re))
    {
        $book_id_number = $row['book_id']."-".$row['fk_area_id']."-".$row['area_counter'];
        $title = $row['title'];
        $author = $row['author'];
        $area = $row['area_name'];
        $status = $row['status'];
        $number_of_copy = $row['number_of_copy'];        
    }
?>
    <div>
        <form onsubmit="" method = "post">
            <fieldset id="fieldset_style">
                <div id="table_header_style">
                    <table border="0" style="width: 100%; height: 100%;font-size: 17px">
                        <tr align="center">
                            <td style="text-align: left; width: 22%; font-size: 20px; color: #FAF6F6; font-family: cursive">Library Management</td>
                            <td style="text-align: right;">  Search:<input type="text" id="search_box_style" name ="search_box_style" /></td>
                        </tr>
                    </table>
                </div>
                <div style="height: 300px; overflow-y: scroll;">
                <table id="info_table" border="0" align="center" width= 99%" cellpadding="5px" cellspacing="0px">
                    <thead>
                    <tr id="table_row_odd">
                        <th width="8%"><b>Book ID</b></th>
                        <th width="25%"><b>Title</b></th>
                        <th width="15%"><b>Author</b></th>
                        <th width="15%"><b>Area</b></th>
                        <th width="5%" style="text-align: center"><b>Edition</b></th>
                        <th width="15%" style="text-align: center"><b>ISBN Number</b></th>
                        <th width="12%"><b>Status</b></th>
                        <th width="5%" style="text-align: center"><b>Copy</b></th>
                    </tr>
                    </thead>
                    <tbody>                    
                        <?php 
                            $sql = "SELECT * FROM book, area where fk_area_id=area_id";

                            $res = mysql_query($sql);
                            
                            $flag = true;

                            while($row = mysql_fetch_array($res))
                            {
                                $book_id_number = $row['book_id']."-".$row['fk_area_id']."-".$row['area_counter'];
                                $title = $row['title'];
                                $author = $row['author'];
                                $area = $row['area_name'];
                                $edition = $row['edition'];
                                $isbn = $row['isbn'];
                                $status = $row['status'];
                                $number_of_copy = $row['number_of_copy']; 
                                
                                if($flag)
                                    $row_style = "table_row_even";
                                else 
                                    $row_style = "table_row_odd";
                        ?>
                            <tr id="<?php echo $row_style;?>">
                                <td><?php echo $book_id_number;?></td>
                                <td><?php echo $title;?></td>
                                <td><?php echo $author;?></td>
                                <td><?php echo $area;?></td>
                                <td style="text-align: center"><?php echo $edition;?></td>
                                <td style="text-align: center"><?php echo $isbn;?></td>
                                <td><?php echo $status;?></td>
                                <td style="text-align: center"><?php echo $number_of_copy;?></td>
                            </tr>
                                
                       <?php  
                                $flag = !$flag;
                            }
                        ?>   
                    </tbody>
                </table>
                </div>
            </fieldset>
        </form>
   <?php 
        $sql = "SELECT book_id FROM book";
        $total_books = mysql_num_rows(mysql_query($sql));
        $sql = "SELECT area_id FROM area";
        $total_area = mysql_num_rows(mysql_query($sql));
        $sql = "select * from book where book_id = (select max(book_id) from book)";
        $row = mysql_fetch_array(mysql_query($sql));
        $latest_book = $row['title'];
        $sql = "select * from area where area_id = (select max(area_id) from area)";
        $row = mysql_fetch_array(mysql_query($sql));
        $latest_area = $row['area_name'];
        $sql = "SELECT sum(number_of_copy) as total_copy FROM book";
        $row = mysql_fetch_array(mysql_query($sql));
        $total_copy = $row['total_copy'];
        
   ?>
        
        <fieldset id ="fieldset_style" style="margin-top: 10px; padding: 10px;" >
            <table border="0" style="width: 100%; font-size: 17px">
                <tr align="center" style="font-family: cursive; color: #0F1648">
                    <td>Total Books<div id="box_style">
                            <div id="box_inside_info" style="padding-top: 30px;"> 
                                <?php echo $total_books;?>
                            </div>
                        </div>                        
                    </td>
                    <td>Total Copies<div id="box_style">
                            <div id="box_inside_info" style="padding-top: 30px;">
                                <?php echo $total_copy;?>
                            </div>
                        </div>
                    </td>
                    <td>Total Area<div id="box_style">
                            <div id="box_inside_info" style="padding-top: 30px;">
                                <?php echo $total_area;?>
                            </div>
                        </div>                        
                    </td>
                    <td>Latest Book <div id="box_style">
                            <div id="box_inside_info">
                                <?php echo $latest_book;?>                                
                            </div>
                        </div>                        
                    </td>
                </tr>
            </table>
        </fieldset>
        
       
<script type="text/javascript">
//initSortTable('myTable',Array('S','N','S','N','S'));

var filter = new DG.Filter({
	filterField : $('search_box_style'),
	filterEl : $('info_table')
});
</script>
        
        
    </div>
    
</body>
</html>

