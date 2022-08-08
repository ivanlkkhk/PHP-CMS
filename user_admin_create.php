<?php 
	// This page is using for create user_account.
	session_start();

	//System user onlly, if not forward to login page.
	if (!($_SESSION['valid'] && $_SESSION['user_type'] === 'S') ){
		header('Location: login.php');	
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>Dragon Ball Z Dokkan Battle - User Admin</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<header>
		<div id="heading">
				<div id="mainMenu">
					<h2>Dragon Ball Z Dokkan Battle - User Admin</h2>
				</div>
		</div>	
	</header>	
	<section>
		<form id="loginForm"
		      action="process_user_post.php" 
		      method="post">
			<fieldset id="login">
				<legend>Enter User Information</legend>
				<ul>
					<li>
						<label for="user_id">User ID</label>
						<input id="user_id" name="user_id" type="text"/>
					</li>
					<li>
						<label for="name">Name</label>
						<input id="name" name="name" type="text"/>
					</li>
					<li>
						<label for="type">Type</label>
						<select name="type">
							<option value="S">System Administrator</option>
							<option value="C">Content Manager</option>
							<option value="R" selected>Registered User</option>
						</select>
					</li>
					<li>
						<label for="password">Password</label>
						<input id="password" name="password" type="password" />
					</li>
					<li>
						<label for="active">Active</label>
						<input id="active" name="active" type="checkbox" checked/>
					</li>
				</ul>
				<div class="buttons">
					<button type="submit" name="command" value="create_user">login</button>
				</div>
			</fieldset>
		</form>
		<footer>
			<div id="footer">
                <p>&copy; 2022 created by Kwok Keung Lai.</p>
			</div>
		</footer>
	</section>
</body>
</html> 