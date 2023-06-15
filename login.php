<!DOCTYPE html>
<html>
<head>
<meta content ="text/html; charset =utf-8" http-equiv ="content-Type">
<link type = text/css href ="css\format.css" rel ="stylesheet">
<script type="text/javascript" src="..\js\jquery-1.10.2.min.js"></script>
<script type="text/javascript">

</script>
<title>Login</title>
</head>
<body>
<?php 
$error ="";
$notice ="";
$flag = false;

if (!empty($_POST)){
    if ($_POST['user_name'] ==""){
        $error .="- Chưa nhập tên đăng nhập.<br>";
    }
    if ($_POST['password'] ==""){
        $error .="- Chưa nhập mật khẩu.<br>";
    }
    if ($_POST['user_name'] != "" && $_POST['password'] !=""){
        require_once 'class/database.class.php';
        $database = new Database();
        $database->setDatabase('tai_khoan');
        $query ='SELECT u.user_name, u.pass_word FROM tai_khoan.user AS u';
        $result = $database->selectQuery($query);
        foreach ($result as $keys => $vals){
            if ($vals['user_name'] == $_POST['user_name'] && $vals['pass_word'] == md5($_POST['password'])){
                $flag = true;
                break;
            }
        }
        if ($flag == true){
           
            header('location:login_success.php');
            exit();
        }else {
            $notice .="- Mật khẩu hoặc tài khoản không đúng.";
        }
        
    }
}

?>
	<div class ="content">
		<h1>LogIn</h1>
		<div class ="form">
			<form action="#" method ="post">
    			<div class ="error">
        			<p>
            			<?php echo $notice .'<br>';
            			         echo $error;
            			?></p>
    			</div>
    			
    			<div class ="row">
        			<p><b>User Name:</b></p>
        			<p><input type ="text" name ="user_name" placeholder ="Nhập tên đăng nhập." value ="<?php echo (isset($_POST['user_name']) ? $_POST['user_name'] :"");?>"></p>
        			<p><b>Password:</b></p>
        			<p><input type ="password" name ="password" placeholder ="Nhập mật khẩu đăng nhập."></p>
    			</div>
        			<div class ="action">
        			<input type ="submit" name ="submit" id ="submit">
        			<input type ="reset">
        			</div>
			</form>
		
		</div>
	
	</div>
</body>
</html>