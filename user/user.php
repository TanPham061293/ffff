<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link type = text/css href ="..\css\format.css" rel ="stylesheet">
<script type="text/javascript" src="..\js\jquery-1.10.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#check_all').change(function(){
		var checkStatus = this.checked;
		$('#form').find(':checkbox').each(function(){
			this.checked = checkStatus;
		});
	});
	$('#delete').click(function(){
			$('#form').submit();
	});
});

</script>
<title>Manage User</title>
</head>
<body>
<?php 
//
require_once '..\class\database.class.php';
$database = new  Database();
//
$notice ="";
$flad = false;
if (isset($_POST['checkbox']) && count($_POST)>=1){
    $flad = true;
    $database->setDatabase('tai_khoan');
    $database->setTable('tai_khoan.user');
    $where_delete ="id IN ('".implode("','", $_POST['checkbox'])."')";
    $database->deleteQuery($where_delete);
    $row =$database->affectRow();
    $notice ="<p class ='success'>- Xóa thành công $row dòng.</p>";
}else if(!isset($_POST['checkbox']) && count($_POST)>=1){
    $notice ="<p class ='error'>- Chưa chọn mục để xóa.</p>";
}
//
$query ='SELECT COUNT(u.id) AS soluong
            FROM tai_khoan.user as u';
$total_element = $database->selectQuery($query);
$count_page = 10;
$positon = 0;
$total_page =ceil($total_element[0]['soluong']/$count_page);
$view_page = 3;
$curent_page = 1;
//
if ($total_page > 1){
    
    if (!empty($_GET)){
        $curent_page = $_GET['page'];
    }
    $positon =($curent_page -1)*$count_page;
    $page ="";
    if ($curent_page < $total_page -1 && $total_page > $view_page){
        $page .= '<li><a id ="end" href ="?page='.$total_page.'">End</a></li><li><a >...</a></li>';
    }else{
        $page .= '<li><a id ="end" href ="?page='.$total_page.'">End</a></li>';
    }
   
    if ($curent_page >=$view_page && $curent_page < $total_page){
        for ($i = $curent_page + 1; $i >= $curent_page -1;$i--){
            if ($i == $curent_page){
                $page .= '<li class = "current"><a  href ="?page='.$i.'">'.$i.'</a></li>';
            }else{
                $page .= '<li><a  href ="?page='.$i.'">'.$i.'</a></li>';
            }  
        }
    }elseif ($curent_page < $view_page){
        if ($total_page <= $view_page){
            for ($i = $total_page ; $i >= 1;$i--){
                if ($i == $curent_page){
                    $page .= '<li class = "current"><a  href ="?page='.$i.'">'.$i.'</a></li>';
                }else{
                    $page .= '<li><a  href ="?page='.$i.'">'.$i.'</a></li>';
                }
            }
        }else {
            for ($i = $view_page ; $i >= 1;$i--){
                if ($i == $curent_page){
                    $page .= '<li class = "current"><a  href ="?page='.$i.'">'.$i.'</a></li>';
                }else{
                    $page .= '<li><a  href ="?page='.$i.'">'.$i.'</a></li>';
                }
            }
        }
        
    }else {
        for ($i = $total_page ; $i > $total_page - $view_page;$i--){
            if ($i == $curent_page){
                $page .= '<li class = "current"><a  href ="?page='.$i.'">'.$i.'</a></li>';
            }else{
                $page .= '<li><a  href ="?page='.$i.'">'.$i.'</a></li>';
            }
        }
    }
    
    $page .= '<li><a id ="end" href ="?page=1">Start</a></li>';
    
}else {
    $page ="";
}
$query ="SELECT u.user_name,CONCAT(u.last_name,' ', u.first_name) AS full_name,u.email, u.id, u.birthday, g.status, g.ordering, g.name
        FROM tai_khoan.user AS u LEFT JOIN tai_khoan.group AS g
        ON u.group_id = g.id
        ORDER BY u.id
        LIMIT $positon , $count_page ";
$result = $database->selectQuery($query);

$table ="";
$i = 0;
foreach ($result as $keys =>$vales){
    $difference = ($i % 2 == 0) ? "even" : "odd";
    if ($vales['birthday'] != null){
        $date = date('d-m-Y',strtotime($vales['birthday']));
    }else{
        $date= "";
    }
   
    $status = $vales['status'] == 0 ?"Action":"Inaction";
    $table .='<div class ="row '.$difference .'">
					<p class ="no"><input type ="checkbox" name ="checkbox[]" value ="'.$vales['id'].'" id ="check_all"></p>
					<p class ="name">'. $vales['user_name'] . '<br> <span>'. $vales['full_name'] .' | '.$vales['email']. '</span></p>
					<p class ="id">' . $vales['id'] . '</p>
					<p class ="size"> '. $date. '</p>
					<p class ="size">' . $status . '</p>
					<p class ="size">' . $vales['ordering'] . '</p>
					<p class ="size">' . $vales['name'] . '</p>
					<p class ="action"><b><a href ="edit_user.php?id='.$vales['id'].'&page='.$curent_page.'">Edit</a>|
                                          <a href ="detail_user.php?id='.$vales['id'].'&page='.$curent_page.'">Detail</a></b></p>
				</div>';
    $i++;
}
?>
	<div class ="content">
		<h1>Manage User</h1>
		<div class ="operation">
            		<a class ="logout" href ="..\login.php">LogOut</a>
            		<a class ="home" href ="..\login_success.php">Home</a>
            		</div>
		<div class ="group">
			
			<form action="#" method ="post" id ="form">
			<?php echo $notice; ?>
				<div class ="row header">
					<p class ="no"><input type ="checkbox" name ="check_all" value ="check_all" id ="check_all"></p>
					<p class ="name"><b>User Name</b></p>
					<p class ="id"><b>ID</b></p>
					<p class ="size"><b>Birthday</b></p>
					<p class ="size"><b>Status</b></p>
					<p class ="size"><b>Ordering</b></p>
					<p class ="size"><b>Group Name</b></p>
					<p class ="action"><b>Operation</b></p>
				</div>
				<?php echo $table;?>
            		<div class ="operation">
                		<input type ="hidden" name ="hidden">
                		<a class ="add" href ="add_user.php">Add User</a>
                		<a class ="delete" href ="#" id ="delete">Delete User</a>
            		</div>
            		<div class ="pagination">
                		<ul>
                		<?php echo $page;?>
                		<li><?php if ($total_page > 1)echo "Page:";?></li>
                		</ul>
            		</div>
			</form></div>
	</div>
</body>
</html>