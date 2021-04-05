<?php
	include "conf/config.php";
	
	if(isset($_GET["submit"])) {
		$username = $_GET["username"];
		$password = $_GET["password"];

		$message = "";

		$sql = "SELECT `id`, `username`, `password` FROM `users`";
		$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		if (!empty($username) && !empty($password)) {

			foreach( $rows as $row) {
				if ($row['username'] === $username && $row['password'] === $password) {
					$_SESSION['user_id'] = $row['id'];
					header("Location: index.php");
				} else {
					$message = "Daxil etdiyiniz username və ya password yanlışdır. Zəhmət olmasa məlumatlarınızı yoxlayın!";		
				}
				
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
			<div class="signIn-wrapper">
				<h3 class="title">Sign In to Chat</h3>
				<form action="#" method="GET" class="signIn-form pt-3">
					<div class="form-group">
					    <label for="username">Username</label>
					    <input name="username" type="text" class="form-control" id="username" placeholder="Enter username">
					</div>
					<div class="form-group">
					    <label for="password">Password</label>
					    <input name="password" type="password" class="form-control" id="password" placeholder="Password">
					</div>
					<button type="submit" name="submit" class="btn btn-math">Sign In</button>
					<?php
						if (!empty($message)) {
							echo "<div class='alert alert-danger mt-5' role='alert'>".$message."</div>";
						}
					?>
				</form>
			</div>
			<div class="upContent left">
				<div class="show-content">
					<div class="signUp-wrap">
						<h4 class="title">Welcome Back!</h4>
						<p class="login-text">To keep connected with us please login with your personal info</p>
						<a href="register.php" class="sign-up">Sign Up</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>