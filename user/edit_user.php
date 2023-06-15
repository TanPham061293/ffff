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
<title>Edit user</title>
</head>
<body>
<?php 
require_once '..\class\database.class.php';
$database = new Database();
$database->setDatabase('tai_khoan');
$id =$_GET['id'];
$notice="";
$error="";
    $query ="SELECT u.user_name,u.last_name, u.first_name, u.email, u.id, u.birthday,u.address,u.sex, g.status, g.ordering, g.name, g.id
    FROM tai_khoan.user AS u LEFT JOIN tai_khoan.group AS g
    ON u.group_id = g.id
    WHERE u.id = $id";
    $result = $database->selectQuery($query);
    
    $query ='SELECT g.id, g.name FROM tai_khoan.group AS g';
    $result_group_id = $database->selectQuery($query);
    $sex ="";
    if ($result[0]['sex'] == 0){
        $sex .= '<option value = 0 >Nữ</option><option value = 1 >Nam</option>';
    }else{
        $sex .= '<option value = 1 >Nam</option><option value = 0 >Nữ</option>';
    }
    $option = "";
    foreach ($result_group_id as $keys => $vals){
        if ($vals['id'] == $result[0]['id']){
            $option .= '<option selected="selected" value ="'. $vals['id'] . '">'.$vals['name'].'</option>';
        }else {
            $option .= '<option value ="'. $vals['id'] . '">'.$vals['name'].'</option>';
        }
    }
   
if (!empty($_POST)) {
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
        $query ='SELECT u.user_name,u.id FROM tai_khoan.user AS u';
        $data = $database->selectQuery($query);
        $check = true;
        foreach ($data as $keys =>$values){
            if ($_POST['user_name'] == $values['user_name'] && $values['id'] != $result['id']){
                $check = false;
                break;
            }
        }
        if ( $check == true){
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
            $Where =array('id' =>$id);
            $database->updateDatabase($change_data, $Where);
            $row =$database->affectRow();
            $notice =$row . '<p class ="success">update data successfully."</p>';
        }else{
            $error ='<ul><li>Thêm Thất bại.Tên đăng nhập đã tồn tại.</li></ul>';
        } 
    }else {
        $notice ='<p class ="error">updata data failed.</p>';
    }
    
}

$url ="user.php?id=".$_GET['id']."&page=".$_GET['page'];
?>
<div class ="content">
		<h1>Edit User</h1>
		<div class ="form">
			<form action="#" method ="post" name ="form_user" id ="form">
				<div class ="row">
				<?php echo $notice .'<br>';
				echo $error;?>
					<p><b>First Name:</b></p>
					<p><input type ="text" name ="first_name" placeholder ="Nhập vào tên gọi." value ="<?php echo (empty($_POST) ? $result[0]['first_name'] :$_POST['first_name']);?>" >	</p>
					<p><b>Last Name:</b></p>
					<p><input type ="text" name ="last_name" placeholder ="Nhập vào họ và tên đệm." value ="<?php echo (isset($_POST['last_name']) ? $_POST['last_name'] :$result[0]['last_name']);?>"></p>
					<p><b>Birthday:</b></p>
					<p><input type ="date" name ="birthday" value ="<?php echo (isset($_POST['birthday']) ? $_POST['birthday'] :$result[0]['birthday']);?>"></p>
					<p><b>Sex:</b></p>
					<p><select name ="sex">
						<?php echo $sex;?>
					</select></p>
					<p><b>Address:</b></p>	
					<p><input type ="text" name ="address" placeholder ="Nhập địa chỉ hiện tại." value ="<?php echo  (isset($_POST['address']) ? $_POST['address'] :$result[0]['address']);?>"></p>
					<p class ="name" ><b>User Name</b></p>
					<p><input type ="text" name = "user_name" placeholder ="Nhập tên đăng nhập." value ="<?php echo (isset($_POST['user_name']) ? $_POST['user_name'] :$result[0]['user_name']);?>">
					<p><b>Password:</b></p>
					<p><input type ="password" name = "password" placeholder ="Nhập mật khẩu đăng nhập."></p>
					<p><b>Group Name:</b></p>
					<p><select name ="group_id">
					<?php echo $option;?>
					</select>
					<p><b>Email:</b></p>	
					<p><input type ="text" name ="email" placeholder ="Nhập địa chỉ email." autocomplete ="off" value ="<?php echo  (isset($_POST['email']) ? $_POST['email'] :$result[0]['email']);?>"></p>	
				</div>
				<hr>
            		<div class ="operation">
            		<input type ="hidden" value ="hidden">
            		<a class ="add" id ="add" href ="#">Update</a>
            		<a class ="cancel" href ="<?php echo $url; ?>">Cancel</a>
            		</div>
			</form>
				
            </div>
	</div>
</body>
</html>