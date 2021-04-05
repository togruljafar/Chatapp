<?php
	include "conf/config.php";
	include "functions.php";

	$login_id = $_SESSION['user_id'];
	$username = $_GET['user'];

	$sql = "SELECT * FROM `users` WHERE `username` = '$username'";
	// $query = mysqli_query($conn, $sql);
	// $row = mysqli_fetch_assoc($query);
	$row = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	$row = $row[0];
	$blocks  = hasBlock($login_id, $db);

	$user_id = $row['id'];
	$name = $row['name'];
	$surname = $row['surname'];
	$email = $row['email'];
	$photo = $row['photo'];
	$job = $row['job'];
	$posts_count = getPostCount($user_id,$db);
	$isBlock = in_array($row['id'], $blocks);
	$isFriend = isFriend($login_id,$user_id,$db);

	if (isset($_POST['block'])) {
		$sql = "INSERT INTO `blocks`(`from_id`, `to_id`) VALUES($login_id, $user_id)";
		// mysqli_query($conn, $sql);
		$adding = $db->prepare($sql)->execute();

		if ($isFriend) {
			// remove from friends
			removeFriend($login_id,$user_id,$db);
		}
		header("Location:index.php");
	} else if (isset($_POST['friend'])) {
		$sql = "INSERT INTO `friends`(`from_id`, `to_id`) VALUES($login_id, $user_id)";
		// $query = mysqli_query($conn, $sql);
		$adding = $db->prepare($sql)->execute();
		
		if ($adding) {
			header("Refresh:0");
		}
	} else if ( $isFriend && isset($_POST['message'])) {
		$hasChat = hasChat($login_id,$user_id,$db);
		if ($hasChat) {
			goChat($login_id,$user_id,$db);
		} else {
			createChat($login_id,$user_id,$db);
		}
	}

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
			<div class="profesionalty"><?php if($isFriend) echo "He is your friends"; ?></div>
		</div>
		<div class="profile-action">
		<?php 
			if(!$isBlock) {
				echo "<form method='POST' action=''>";
				if(!$isFriend) {
					echo "<button name='friend' class='btn btn-primary follow-btn'>Add Friend</button>";
					echo "<button name='block' class='btn btn-danger block-btn'>Block User</button>";
				} else {
					echo "<button name='block' class='btn btn-danger block-btn'>Block User</button>";
					echo "<button name='message' class='btn btn-success message-btn'>Send Message</button>";
				}
				echo "</form>";
			}
		?>
		</div>
		<?php 
			if($isBlock) {
				echo "<div class='alert alert-danger my-3' role='alert'>Acount was BLocked!</div>";
			} 
		?>
		<hr>
		<ul class="nav d-flex">
			<li class="active"><a href="">Posts (<span><?=$posts_count?></span>)</a></li>
			<li><a href="">Friends (<span>83</span>)</a></li>
			<li><a href="">About</a></li>
		</ul>
	</div>
</body>
</html>