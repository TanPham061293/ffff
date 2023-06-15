

<!DOCTYPE html>
<html>
<head>
<meta content ="text/html; charset = utd-8" http-equiv ="content-Type">
<link type = text/css href ="..\css\format.css" rel ="stylesheet">
<script type="text/javascript" src="..\js\jquery-1.10.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#add').click(function(){
		$('#form').submit();
		});
})

</script>
<title>Add user</title>
</head>
<body>
<div class ="content">
<?php 
$notice ="";
$error  ="";
require_once '..\class\database.class.php';
$database = new Database();
$query ='SELECT g.id, g.name FROM tai_khoan.group AS g';
$data = $database->selectQuery($query);
$option_group_id ="";
foreach ($data as $key =>$vals){
    $option_group_id .='<option value ="'.$vals['id'].'">'.$vals['name'].'</option> .';
}
if (!empty($_POST)){
    require_once '..\class\validate.class.php';
    $validate =new Validate($_POST);
    
    $condition = array(
                    'first_name'  =>array('type' =>'string', 'min' =>'1','max' =>'20'),
                    'last_name'   =>array('type' =>'string', 'min' =>'1','max' =>'30'),
                    'birthday'    =>array('type' =>'date'),
                    'address'     =>array('type' =>'string', 'min' =>'1','max' =>'100'),
                    'user_name'   =>array('type' =>'string', 'min' =>'1','max' =>'50'),
                    'password'    =>array('type' =>'password'),
                    'email'       =>array('type' =>'email')
                             );
    $validate->setRules($condition);
    $validate->run();
    $result =$validate->getResult();
    $error =$validate->showError();
    
    if (empty($error)){
        $database->setDatabase('tai_khoan');
        $database->setTable('tai_khoan.user');
        $query ='SELECT u.user_name FROM tai_khoan.user AS u';
        $data = $database->selectQuery($query);
        $check = true;
        foreach ($data as $keys =>$values){
            if ($_POST['user_name'] == $values['user_name']){
                $check = false;
                break;
            }
        }
        if ($check == true){
            unset($_POST['submit']);
            $_POST['password'] = md5($_POST['password']);
            $_POST['birthday'] = date('Y-m-d',strtotime($_POST['birthday']));
           
            $change_data = array(
                'first_name'   =>$_POST['first_name'],
                'last_name'    =>$_POST['last_name'],
                'birthday'     =>$_POST['birthday'],
                'user_name'    =>$_POST['user_name'],
                'pass_word'    =>$_POST['password'],
                'email'        =>$_POST['email'],
                'address'      =>$_POST['address'],
                'sex'          =>$_POST['sex'],
                'group_id'     =>$_POST['group_id']
            );
            $database->insertDatabase($change_data);
            $row =$database->affectRow();
            $notice =$row . '<p class ="success">insert data successfully."</p>';
        }else{
            $error ='<ul><li>Thêm Thất bại.Tên đăng nhập đã tồn tại.</li></ul>';
        }      
    }else {
        $notice ='<p class ="error">insert data failed.</p>';
    }
}

?>
		<h1>Add User</h1>
		<div class ="form">
			<form action="#" method ="post" name ="form_user" id ="form">
				<div class ="row">
				<?php echo $notice .'<br> ' ;
				        echo $error;?>
					<p><b>First Name:</b></p>
					<p><input type ="text" name ="first_name" placeholder ="Nhập vào tên gọi." value ="<?php echo (empty($result['first_name']) ? "" : $result['first_name']); ?>">	</p>
					<p><b>Last Name:</b></p>
					<p><input type ="text" name ="last_name" placeholder ="Nhập vào họ và tên đệm." value ="<?php echo (empty($result['last_name']) ? "" : $result['last_name']); ?>"></p>
					<p><b>Birthday:</b></p>
					<p><input type ="date" name ="birthday" value ="<?php echo (empty($result['birthday']) ? "" : $result['birthday']); ?>"></p>
					<p><b>Sex:</b></p>
					<p><select name ="sex">
						<option value ="1">Nam</option>
						<option value ="0">Nữ</option>
					</select></p>
					<p><b>Address:</b></p>	
					<p><input type ="text" name ="address" placeholder ="Nhập địa chỉ hiện tại." value ="<?php echo (empty($result['address']) ? "" : $result['address']); ?>"></p>
					<p class ="name" ><b>User Name</b></p>
					<p><input type ="text" name = "user_name" placeholder ="Nhập tên đăng nhập." value ="<?php echo (empty($result['user_name']) ? "" : $result['user_name']); ?>">
					<p><b>Password:</b></p>
					<p><input type ="password" name = "password" placeholder ="Nhập mật khẩu đăng nhập."></p>
					<p><b>Group Name:</b></p>
					<p><select name ="group_id">
						<?php 
						echo $option_group_id;
						?>
					</select></p>	
					<p><b>Email:</b></p>	
					<p><input type ="text" name ="email" placeholder ="Nhập địa chỉ email." autocomplete ="off" value ="<?php echo (empty($result['email']) ? "" : $result['email']); ?>"></p>	
				</div>
				<hr>
            		<div class ="operation">
            		<input type ="hidden" name ="hidden" >
            		<a class ="add" href ="#" id ="add">Add</a>
            		<a class ="cancel" href ="user.php">Cancel</a>
            		</div>
			</form></div>
	</div>
</body>
</html>