
<!DOCTYPE html>
<html>
<head>
<meta content ="text/html; charset = utd-8" http-equiv ="content-Type">
<link type = text/css href ="..\css\format.css" rel ="stylesheet">
<title>Edit Group</title>
</head>
<body>
<?php 
echo '<pre>';
print_r($_GET);
echo '</pre>';
$index =$_GET['id'];
$flag = false;
$fail ="";
$result ="";
$notice ="";
require_once '..\class\database.class.php';
$database = new Database();
if(!empty($_POST)){
    require_once '..\class\validate.class.php';
    $validate = new Validate($_POST);
        $condition =array(
            'name' =>array('type'=>'string','min'=>'1', 'max'=>'50'),
            'ordering' =>array('type'=>'numeric','min'=>'1', 'max'=>'10'),
        );
        $validate->setRules($condition);
        $validate->run();
        $error = $validate->getError();
        $result =$validate->getResult();
        if (empty($error)){
            $flag = true;
           
            
            $query = "SELECT name,id FROM tai_khoan.group";
            $checkData = $database->selectQuery($query);
            foreach ($checkData as $a => $b){
                if (strtolower($b['name']) == strtolower($_POST['name']) &&$b['id'] != $index){
                    $flag = false;
                    $fail .="Tên nhóm đã tồn tại.";
                    break;
                }
            }
        }else{
            foreach ($error as $key =>$vals){
                $fail = "- $vals<br>";
            }
    }
} 
    if ($flag == true ){
        $database->setDatabase('tai_khoan');
        $database->setTable('tai_khoan.group');
        
        unset($_POST['submit']);
        $where =array('id'=>"$index");
        $database->updateDatabase($_POST, $where);
        
        $notice .="Update thành công";
    
}else{
    
    $query = "SELECT * FROM tai_khoan.group WHERE id = $index";
    $checkData = $database->selectQuery($query);
    $name = $checkData[0]['name'];
    $status = $checkData[0]['status'];
    if($status == 0){
        $select = 'selected ="selected"';
        $select1 = "";
    }else{
        $select1 = 'selected ="selected"';
        $select = "";
    }
    $ordering = $checkData[0]['ordering'];
}
$url ="group.php?id=".$_GET['id']."&page=".$_GET['page'];

?>
<div class ="content">
		<h1>Edit Group</h1>
		<div class ="form">
			<form action="#" method ="post">
		<?php if ($flag == false){ echo "<p class ='error'>$fail </p>";}else{
					    echo "<p class ='success'>$notice </p>";}?>
				<div class ="row">
					<p class ="name" >Group Name</p>
					<p><input type ="text" name = "name" placeholder ="Nhập tên nhóm." value ="<?php echo (empty($_POST)?$name:$_POST['name']);?>">		
					<p class ="size">Status</p>
					<p><select name = "status">
					<option value = "1" <?php echo (empty($_POST)?$select1:($_POST['status']== 1?'selected ="selected"':""));?>>Inaction</option>
                	<option value = "0" <?php echo (empty($_POST)?$select :($_POST['status']== 0?'selected ="selected"':""));?>>Action</option>
					</select></p>
					<p class ="size" >Ordering</p>
					<p><input type ="text" name ="ordering" placeholder ="1->10." value ="<?php echo (empty($_POST)?$ordering:$_POST['ordering']);?>">
				</div>
            		<div class ="operation">
            		<input type ="submit" name ="submit" value ="Update">
            		<a class ="cancel" href ="<?php echo $url;?>">Cancel</a>
            		</div>
			</form></div>
	</div>
</body>
</html>