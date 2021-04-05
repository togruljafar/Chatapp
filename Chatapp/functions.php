<?php 
	// get login user datas
	function getLoginUser($login_id, $db) {
		$sql = "SELECT * FROM `users` WHERE `id` = '$login_id'";
		$row = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		
		$userInfo = [
			'id' => $login_id,
			'name' => $row[0]['name'],
			'surname' => $row[0]['surname'],
			'email' => $row[0]['email'],
			'username' => $row[0]['username'],
			'job' => $row[0]['job'],
			'photo' => $row[0]['photo']
		];

		return $userInfo;

	}
	
	// check that has or not block
	function hasBlock($login_id, $db) {
		$sql = "SELECT * FROM `blocks`";
		$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		$blocks = [];
		$i = 0;
		foreach ($rows as $row) {
			if($row['from_id'] === $login_id) {
				$blocks[$i] = $row['to_id'];
				$i++;
			}else if($row['to_id'] === $login_id) {
				$blocks[$i] = $row['from_id'];
				$i++;
			}
		}
		return $blocks;	
	}

	// get user datas
	function getUsers($login_id, $db) {
		$sql = "SELECT * FROM `users`";
		$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		$blocks = hasBlock($login_id, $db);
		$users = [];
		$i = 0;

		foreach ($rows as $row) {
			if (!in_array($row['id'], $blocks) && $row['id'] !== $login_id) {
				$users[$i] = $row;
				$i++;
			}
			
		}

		return $users;
	}

	// get post datas
	function getPosts($login_id, $db) {
		$sql = "SELECT * FROM `posts` ORDER BY `share_time` DESC";
		$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		$blocks = hasBlock($login_id, $db);
		$posts = [];
		$i = 0;

		foreach ($rows as $row) {
			if (!in_array($row['by_id'], $blocks)) {
				$getPostBy = getPostBy($row, $db);
				$post = [
					'id' => $row['id'],
					'by_id' => $row['by_id'],
					'upload' => $row['upload'],
					'description' => $row['description'],
					'share_time' => $row['share_time'],
					'name' => $getPostBy['name'],
					'surname' => $getPostBy['surname'],
					'photo' => $getPostBy['photo']
				];

				$posts[$i] = $post;
				$i++;
			}
		}

		return $posts;
	}
	// find x-th post which user posted
	function getPostBy($post, $db) {
		$by_id = $post['by_id'];
		$sql = "SELECT `name`, `surname`, `photo` FROM `users` WHERE `id` = $by_id";
		$row = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		return $row[0];
	}

	// get count of one's posts
	function getPostCount($user_id, $db) {
		$sql = "SELECT * FROM `posts` WHERE `by_id` = $user_id";
		$row = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		$count = 0;
		foreach ($row as $value) {
			$count++;
		}
		
		return $count;
	}

	// check login user and x user are friend or not
	function isFriend($login_id,$user_id,$db) {
		$sql = "SELECT * FROM `friends` WHERE `to_id` = $login_id OR `from_id` = $login_id";
		$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		
		foreach ($rows as $row) {
			if ($row['from_id'] === $user_id || $row['to_id'] === $user_id) {
				return true;
			}		
		}
	}

	// remove user from friends list --- we need also remove from friends after block user 
	function removeFriend($login_id,$user_id,$db) {
		$sql = "SELECT * FROM `friends` WHERE `to_id` = $login_id OR `from_id` = $login_id";
		$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		foreach ($rows as $row) {
			if ($row['from_id'] === $user_id || $row['to_id'] === $user_id) {
				$friends_id = $row['id'];
				break;
			}
		}
		// ********************************************************* //
		$sql = "DELETE FROM `friends` WHERE `id` = ?";
		$stmt = $db->prepare($sql);
		$stmt->execute([$friends_id]);
		$deleted = $stmt->rowCount();
	}

	// check has chat room or not
	function hasChat($login_id,$user_id,$db) {
		$sql = "SELECT * FROM `chats` WHERE `user_1` = $login_id OR `user_2` = $login_id";
		$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		
		foreach ($rows as $row) {
			if ($row['user_2'] === $user_id || $row['user_1'] === $user_id) {
				return true;
			}		
		}
	}

	// find chat room and go to there if has chat room
	function goChat($login_id,$user_id,$db) {
		$sql = "SELECT * FROM `chats` WHERE `user_1` = $login_id OR `user_2` = $login_id";
		$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		foreach ($rows as $row) {
			if ($row['user_2'] === $user_id || $row['user_1'] === $user_id) {
				$chat_id = $row['id'];
				break;
			}		
		}
		header("Location: chat.php?chat=$chat_id");
	}

	// create chat room
	function createChat($login_id,$user_id,$db) {
		$sql = "INSERT INTO `chats`(`user_1`, `user_2`) VALUES($login_id, $user_id)";
		$adding = $db->prepare($sql)->execute();

		if ($adding) {
			goChat($login_id,$user_id,$db);
		}
	}

	// get messages in chat room for chat_id
	function getMessage($chat_id, $db) {
		$sql = "SELECT * FROM `chat` WHERE `chat_id` = $chat_id ORDER BY `time` ASC";
		$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

		return $rows;
	}
?>