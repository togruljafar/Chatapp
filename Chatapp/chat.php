<?php
	include "conf/config.php";
	include "functions.php";
	
	$login_id = $_SESSION['user_id'];
	$chat_id = $_GET['chat'];

	$user = getLoginUser($login_id, $db);
	$messages = getMessage($chat_id, $db);

	// send message 
	if (isset($_POST['send_message']) && !empty($_POST['message'])) {
		$message = $_POST['message'];

		$sql = "INSERT INTO `chat`(`message`,`chat_id`,`by_id`) VALUES('$message', $chat_id, $login_id)";
		$adding = $db->prepare($sql)->execute();

		if ($adding) {
			header("Refresh:0");
		} else {
			echo $sql;
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
	<div class="chat-page">
		<div class="right-wrap w-100">
			<div class="header d-flex align-items-center justify-content-center">
				<div class="profile-image">
					<img <?="src='assets/images/".$user['photo']."'";?> alt="">
				</div>
				<div class="description">
					<div class="name"><?=$user['name']." ".$user['surname']?></div>
				</div>
			</div>
			<div class="post-container">
				<div class="messages">
					<?php
						foreach ($messages as $message) {
							if ($message['by_id'] === $login_id) {
								$class = "me-msg";
							} else {
								$class = "";
							}
							echo "<div class='message mb-3'>
									<div class='msg-content ".$class."'>
										<p class='msg-text'>".$message['message']."</p>
										<p class='send-time'>at ".$message['time']."</p>
									</div>
								</div>";
								
						}
					?>
				</div>
				<div class="create-msg">
					<div class="create-msg-content">
						<form action="" method="POST">
							<textarea name="message" class="textarea" cols="100%" rows="2"></textarea>
							<button name="send_message" class="send-btn" >Send</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		// auto scroll to bottom after adding new message 
		var element = document.querySelector(".post-container");
		element.scrollTop = element.scrollHeight;
	</script>
</body>
</html>