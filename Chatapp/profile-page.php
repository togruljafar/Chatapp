<?php
	include "conf/config.php";
	include "functions.php";

	$login_id = $_SESSION['user_id'];

	$sql = "SELECT * FROM `users` WHERE `id` = $login_id";
	$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	$row= $rows[0];
	
	$user_id = $row['id'];
	$name = $row['name'];
	$surname = $row['surname'];
	$email = $row['email'];
	$photo = $row['photo'];
	$job = $row['job'];
	$posts_count = getPostCount($user_id,$db);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat</title>
	<link rel="stylesheet" href="assets/css/reset.css">
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/all.css">
</head>
<body>
	<div class="profile-page-container">
		<div class="header">
			<div class="cover-photo">
				
			</div>
			<div class="profile-img">
				<img <?="src='assets/images/".$photo."'";?> alt="">
			</div>
		</div>
		<div class="profile-desc">
			<div class="name"><?=$name." ".$surname;?></div>
			<div class="profesionalty"><?=$job;?></div>
		</div>
		<hr>
		<ul class="nav d-flex">
			<li class="active"><a href="">Posts (<span><?=$posts_count?></span>)</a></li>
			<li><a href="">Friends (<span>83</span>)</a></li>
			<li><a href="">About</a></li>
		</ul>
	</div>
</body>
</html>