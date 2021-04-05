<?php
	include "conf/config.php";
	include "functions.php";
		
	$login_id = $_SESSION['user_id'];
	$message = "";

	if (isset($login_id)) {

		$loginUser = getLoginUser($login_id, $db);
		$users = getUsers($login_id, $db);
		$posts = getPosts($login_id, $db);
		
		if (isset($_POST['submit'])) {
			if (!empty($_POST['description'])) {
				$by_id = $login_id;
				$description = $_POST['description'];
				$sql = "INSERT INTO posts(`by_id`, `description`) VALUES ($by_id, '$description')";
				$adding = $db->prepare($sql)->execute();

				if($adding) {
			      header('Location: index.php');
			    } else {
			      echo $sql;
			    }
			} else {
				$message = "Zəhmət olmasa mətn daxil edin!"; 
			}
		}

		if(isset($_GET['sign_out'])) {
			unset($_SESSION['user_id']);
			header("Location: login.php");
		}
	} else {
		header("Location: login.php");
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
	<div class="main-page">
		<div class="left-wrap">
			<!-- Login acount -->
			<div class="profile">
				<div class="profile-image">
					<img <?="src='assets/images/".$loginUser['photo']."'";?> alt="" >
				</div>
				<a href='profile-page.php' class="profile-desc">
					<p class="name"><?=$loginUser['name']." ".$loginUser['surname']?></p>
					<p class="professionalty"><?=$loginUser['job']?></p>
				</a>
				<form class="signout-form" action="" method="GET">
					<button name="sign_out" class="signout-btn"><i class="fas fa-sign-out-alt"></i></button>
				</form>
			</div>
			<hr>
			<!-- other user accounts -->
			<div class="users">
				<?php
					foreach ($users as $user) {
						echo "<a href='user-profile.php?user=".$user['username']."' class='user'>
								<div class='pic'>
									<img src='assets/images/".$user['photo']."' alt=''>
								</div>
								<div class='profile-desc'>
									<p class='name'>".$user['name']." ".$user['surname']."</p>
									<!--<p class='professionalty'>".$user['job']."</p>-->
								</div>
								<div class='show-btn'><i class='far fa-eye'></i></div>
							</a>";
					}

				?>
			</div>
		</div>
		<div class="right-wrap">
			<div class="navbar">
				<h3 class="page-title">Welcome Everyone!</h3>
			</div>
			<div class="post-container">
				<div class="create-post">
					<div class="profile-image">
						<img src="assets/images/profi.jpg" alt="">
					</div>
					<button class="create-post-btn">What's on your mind? <span>John</span></button>
					<!-- show items after click -->
					<div class="profile-desc inpost-desc d-none">
						<p class="name">John Adams</p>
						<p class="professionalty">Web Developer</p>
					</div>
					<button class="close-btn d-none">
						<span class="close">
							<span class="slash"></span><span class="slash"></span>
						</span>
					</button>
					<form action="" method="POST"  class="create-post-form w-100 d-none">
						<div class="form-group mt-4">
					    	<textarea name="description" id="textarea" cols="100%" rows="4" class="textarea form-control" placeholder="Enter your mind..."></textarea>
						</div>
						<?php
							if (!empty($message)) {
								echo "<div class='alert alert-danger my-3' role='alert'>".$message."</div>";
							}
						?>
						<button name="submit" class="share-post-btn">Share post</button>
					</form>
				</div>
				<hr>
				<div class="posts">
					<?php
						foreach ($posts as $post) {
							echo "<div class='post'>
									<div class='post-header'>
										<div class='profile-image'>
											<img src='assets/images/".$post['photo']."' alt=''>
										</div>
										<div class='profile-desc'>
											<p class='name'>".$post['name']." ".$post['surname']."</p>
											<div class='shared-date'>
												<div class='time'>".$post['share_time']."</div>
												<div class='dot'></div>
												<div class='address'>Baku</div>
											</div>
										</div>
									</div>
									<div class='post-main'>";
							if (!empty($post['upload'])) {
								echo "<div class='post-image'>
										<img src='assets/images/".$post['upload']."' alt=''>
									</div>";
							}
							echo  $post['description']." <br>
										<span>#Lorem #loremipsum</span>
									</div>
									<div class='post-action'>
										<div class='like'><i class='far fa-thumbs-up'></i> Like</div>
										<div class='comment'><i class='far fa-comment-alt'></i> Comment</div>
										<div class='share'><i class='fas fa-share'></i> Share</div>
									</div>
								</div>";
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<script src="assets/js/main.js"></script>
</body>
</html>