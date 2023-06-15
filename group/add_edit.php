
<!DOCTYPE html>
<html>
<head>
<meta content ="text/html; charset = utd-8" http-equiv ="content-Type">
<link type = text/css href ="..\css\format.css" rel ="stylesheet">
<title>Add Group</title>
</head>
<body>
<?php 
$fail ="";
$result ="";
$notice ="";
$flag = false;

if (!empty($_POST)){
    require_once '..\class\validate.class.php';
    $validate = new Validate($_POST);
    $condition =array(
                       'name'     =>array('type'=>'string','min'=>'1', 'max'=>'50'),
                       'ordering' =>array('type'=>'numeric','min'=>'1', 'max'=>'10'),
                         );
    $validate->setRules($condition);
    $validate->run();
    $error = $validate->getError();
    $result =$validate->getResult();
    if (empty($error)){
        $flag = true;
       
        require_once '..\class\database.class.php';
        $database =new Database();
        $query = "SELECT name FROM tai_khoan.group";
        $checkData = $database->selectQuery($query);
        foreach ($checkData as $a => $b){
            if (strtolower($b['name']) == strtolower($_POST['name'])){
                $flag = false;
                $fail .="Tên nhóm đã tồn tại.";
            }
        }
    }else{
        foreach ($error as $key =>$vals){
            $fail .= "- $vals <br>";
        }
    }
}

    if ($flag == true ){
        
        $database->setDatabase('tai_khoan');
        $database->setTable('tai_khoan.group');
        unset($_POST['submit']);
        $database->insertDatabase($_POST);
        $notice .="Insert thành công";
    }
?>
<div class ="content">

		<h1>Add Group</h1>
		<div class ="form">
			<form action="#" method ="post">
			
			
				<div class ="row">
					<?php 
					if ($flag == false){ echo "<p class ='error'>$fail </p>";}else{
					    echo "<p class ='success'>$notice </p>";}
            			?>
					<p class ="name" ><b>Group Name</b></p>
					<p><input type ="text" name = "name" placeholder ="Nhập tên nhóm." value ="<?php echo (empty($result['name'])?"":$result['name']);?>">		
					<p class ="size"><b>Status</b></p>
					<p><select name = "status" class ="status">
					<option value = '0'>Action</option>
					<option value = '1'>Inaction</option>
					</select></p>
					<p class ="size" ><b>Ordering</b></p>
					<p><input type ="text" name ="ordering" placeholder ="1->10." value ="<?php echo (empty($result['ordering'])?"":$result['ordering']);?>">
				</div>
            		<div class ="operation">
            		<input type ="submit" name ="submit" value ="Add">
            		<a class ="cancel" href ="group.php">Cancel</a>
            		</div>
			</form></div>
	</div>
</body>
</html>