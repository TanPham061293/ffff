<!DOCTYPE html>
<html>
<head>
<meta content ="text/html; charset =utf-8" http-equiv ="content-Type">
<link type = text/css href ="css\format.css" rel ="stylesheet">
<script type="text/javascript" src="js\jquery-1.10.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('div.row a').click(function(){
		$('#form').submit();
		});
});

</script>
<title>Manage Information</title>
</head>

<body>

	<div class ="">
		<h1>Manage Information</h1>
		<div class ="form1">
			<form action="#" method ="post" id ="form">
    			<div class ="row">
    			<input type ="hidden" name ="hidden">
        			<ul>
        			<li><a href ="group/group.php" id ="group" >Information Group.</a></li>
        			<li><a href ="user/user.php" id ="user" >Information User.</a></li>
        			</ul>
    			</div>
			</form>	
			</div>
	</div>
</body>
</html>