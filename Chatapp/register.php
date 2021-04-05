<?php
	include "conf/config.php";
		
	if(isset($_POST["submit"])) {
		$name 	  = $_POST["name"];
		$surname  = $_POST["surname"];
		$username = $_POST["username"];
		$email 	  = $_POST["email"];
		$password = $_POST["password"];

		$message  = "";
		$pass 	  = true;

		$sql = "SELECT `username` FROM `users`";
		$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		foreach( $rows as $row) {
			if($row['username'] === $username) {
				$pass = false;
				break;
			} else {
				$pass = true;
			}
			
		}

		if (!empty($username) && !empty($password) && !empty($name) && !empty($surname) && !empty($email)) {
			if($pass) {

				$sql = "INSERT INTO `users`(`name`, `surname`, `email`, `username`, `password`)VALUES('$name', '$surname', '$email', '$username', '$password')";
				$adding = $db->prepare($sql)->execute();
				
				if ($adding) {
					$sql = "SELECT `id` FROM `users` WHERE `username`='$username'";
					$result = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
					$_SESSION['user_id'] = $result[0]['id'];
					
					header("Location: http://localhost/lesson/Texlab/chatApp/index.php");
				}
			} else {
				$message = "Daxil etdiyiniz 'username' artıq istifadə edilibdir. Zəhmət olmasa fərqli 'username' daxil edin.";
			}
		} else {
			$message = "Zəhmət olmasa məlumatları tam daxil edin!";		
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
	<div class="login-container">
		<div class="login-content">
			<div class="signUp-wrapper">
				<h3 class="title">Sign Up to Chat</h3>
				<form action="" method="POST" class="signUp-form pt-3">
					<div class="d-flex">
						<div class="form-group pr-1">
					    	<label for="name">Name</label>
						    <input name="name" type="text" class="form-control" id="name" placeholder="Enter name">
						</div>
						<div class="form-group pl-1">
					    	<label for="surname">Surname</label>
						    <input name="surname" type="text" class="form-control" id="surname"placeholder="Enter surname">
						</div>
					</div>
					<div class="form-group">
					    <label for="email">Email address</label>
					    <input name="email" type="email" class="form-control" id="email"placeholder="Enter email">
					</div>
					<div class="d-flex">
						<div class="form-group pr-1">
						    <label for="username">Username</label>
						    <input name="username" type="text" class="form-control" id="username" placeholder="Enter username">
						</div>
						<div class="form-group pl-1">
						    <label for="password">Password</label>
						    <input name="password" type="password" class="form-control" id="password" placeholder="Password">
						</div>
					</div>
					<button type="submit" name="submit" class="btn btn-math">Sign Up</button>
					<?php
						if (!empty($message)) {
							echo "<div class='alert alert-danger mt-5' role='alert'>".$message."</div>";
						}
					?>
				</form>
			</div>
			<div class="upContent right">
				<div class="show-content">
					<div class="signIn-wrap">
						<h4 class="title">Hello Friend!</h4>
						<p class="login-text">Enter your personal details and start journey with us..</p>
						<a href="login.php" class="sign-in">Sign In</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>