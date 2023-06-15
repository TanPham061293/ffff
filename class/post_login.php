<?php
require_once 'database.class.php';
$database = new Database();
$database->setDatabase('tai_khoan');
$query ='SELECT u.user_name, u.pass_word FROM tai_khoan.user AS u';
$result = $database->selectQuery($query);
echo '<pre>';
print_r($result);
echo '</pre>';