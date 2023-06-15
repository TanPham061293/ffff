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
	
});

</script>
<title>Manage Group</title>
</head>
<body>
<?php 
    require_once '..\class\database.class.php';
    $database = new  Database();
    $query ='SELECT COUNT(g.id) AS soluong
            FROM tai_khoan.group as g';
    $total_element = $database->selectQuery($query);
    $count_page = 5;
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
        }elseif ($curent_page <$view_page){
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
    $query ='SELECT g.id, g.name, g.ordering, g.status, COUNT(u.group_id) AS member
            FROM tai_khoan.group as g LEFT JOIN tai_khoan.user as u ON g.id = u.group_id
            GROUP BY g.id
            ORDER BY g.id ASC
            LIMIT '. $positon .','.$count_page.'';
    
    $result = $database->selectQuery($query); 
    
//
$notice ="";
$error  ="";
$flag = false;

if (!isset($_POST['checkbox']) && count($_POST) == 1){
    $error .= "Chưa chọn mục xóa";
}elseif(count($_POST) > 1 && isset($_POST['checkbox'])) {
    $flag = true;
    foreach ($result as $keys => $vals){  
        foreach ($_POST['checkbox'] as $keys1 => $vals1){
            if ($vals['id'] ==$vals1){
                if ($vals['member'] != 0){
                    $flag = false;
                    break;
                }
            }
        }  if ($flag == false){break;}
    }
    if ($flag == true){
        $str ="id IN ('". implode("','", $_POST['checkbox'])."')";
        
        $database->setTable('tai_khoan.group');
        $database->deleteQuery($str);
        $row = $database->affectRow();
        $notice .= "Đã xóa $row dòng.";
        $location = ($row == count($result)) ? 'group.php?page='.($curent_page -1).'' :'group.php?page='.$curent_page.'';
        header('location:'.$location);    
    }else{
        $error .= "Không thể xóa nhóm khi còn thành viên trong nhóm.";
    }  
}
$data ="";
$i = 0;
foreach ($result as $keys => $vals){
    $difference =($i % 2 == 0) ? "even" : "odd";
    $status = ($vals['status'] == 0) ? "Action" :"Inaction";
    $data .= '<div class ="row '.$difference .'">
                <p class ="no"><input type ="checkbox" name ="checkbox[]" value ="'.$vals['id'].'"></p>
                <p class ="name">'.$vals['name'].'    </p>
                <p class ="id">'  .$vals['id'].'      </p>
                <p class ="size">'.$vals['member'].'  </p>
                <p class ="size">'.$status.'          </p>
                <p class ="size">'.$vals['ordering'].'</p>
                <p class ="action">
                    <a href ="edit.php?id='.$vals['id'] .'&page='.$curent_page.'">Edit</a>
                    </p> </div>';
    $i++;
}
?>
	<div class ="content">
		<h1>Manage Group</h1>
		<?php if ($flag == false){ echo "<p class ='error'>- $error </p>";}else{
		    echo "<p class ='success'>- $notice $error </p>";}?>
		    <div class ="operation">
            		<a class ="logout" href ="..\login.php">LogOut</a>
            		<a class ="home" href ="..\login_success.php">Home</a>
            		</div>
		<div class ="group">
			
			<form action="#" method ="post" id ="form">
				<div class ="row header">
					<p class ="no"><input type ="checkbox" name ="check_all" value ="check_all" id ="check_all"></p>
					<p class ="name">Group Name</p>
					<p class ="id">ID</p>
					<p class ="size">Member</p>
					<p class ="size">Status</p>
					<p class ="size">Ordering</p>
					<p class ="action">Operation</p>
				</div>
				
				<?php echo $data;?>
				
            		<div class ="operation">
            		<a class ="add" href ="add_edit.php">Add Group</a>
            		<input type ="submit" name ="submit" value ="Delete Group">
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