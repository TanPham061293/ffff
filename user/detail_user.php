<!DOCTYPE html>
<html>
<head>
<meta content ="text/html; charset = utd-8" http-equiv ="content-Type">
<link type = text/css href ="..\css\format.css" rel ="stylesheet">
<script type="text/javascript" src="..\js\jquery-1.10.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#delete').click(function(){
		$('#form_user').submit();
		alert("Xóa thành công.");
		
		});
})
</script>
<title>Detail user</title>
</head>
<body>
<?php 
$id = $_GET['id'];
echo $id;
require_once '..\class\database.class.php';
$database = new Database();
$database->setDatabase('tai_khoan');
$query ="SELECT u.user_name,u.last_name, u.first_name, u.email, u.id, u.birthday,u.address,u.sex, g.status, g.ordering, g.name, g.id
FROM tai_khoan.user AS u LEFT JOIN tai_khoan.group AS g
ON u.group_id = g.id
WHERE u.id = $id";
$result = $database->selectQuery($query);
$sex ="";
if ($result[0]['sex'] == 0){
    $sex ="Nữ";
}else{
    $sex ="Nam";
    }
if (!empty($_POST)){
    $database->setTable('tai_khoan.user');
    $where = array('id' =>$id);
    $database->deleteQuery($where);
    header('location:user.php');
}
$url ="user.php?id=".$_GET['id']."&page=".$_GET['page'];
?>
<div class ="content">
		<h1>Detail User</h1>
		<div class ="form">
			<form action="#" method ="post" name ="form_user" id ="form_user">
				<div class ="row">
					<p><b>First Name:</b><label><?php echo $result[0]['first_name'];?></label>  </p>
					<p><b>Last Name:</b><label><?php echo $result[0]['last_name'];?>  </label>	</p>	
					<p><b>Birthday:</b><label><?php echo $result[0]['birthday'];?>    </label>	</p>	
					<p><b>Sex:</b><label><?php echo $sex;?>						      </label>	</p>
					<p><b>Address:</b><label><?php echo $result[0]['address'];?>      </label>	</p>
					<p><b>User Name:</b><label><?php echo $result[0]['user_name'];?>  </label>  </p>
					<p><b>Group Name:</b><label><?php echo $result[0]['name'];?>      </label>  </p>
					<p><b>Email:</b><label><?php echo $result[0]['first_name'];?>     </label>	</p>		
				</div>
				<hr>
				<div class ="operation">
            		<a class ="delete" href ="#" id ="delete">Delete</a>
            		<a class ="cancel" href ="<?php echo $url; ?>">Cancel</a>
            		<input type ="hidden" name ="hidden">
            		</div>	
			</form>
			
			</div>
	</div>
</body>
</html>