

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>Dragon Ball Z Dokkan Battle - Login</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

	<header>
		<div id="heading">
				<div id="mainMenu">
					<h2>Dragon Ball Z Dokkan Battle - Login</h2>
				</div>
			<div id="image">
				<img class="headerImage" src="images/banner.jpg" alt="Dragon Ball Z Dokkan Battle">
			</div>
		</div>	
	</header>	
	<section>
		<form id="loginForm"
		      action="authenticate.php" 
		      method="post">
			<fieldset id="login">
				<legend>Enter Username and Password</legend>
				<ul>
					<li>
						<label for="user_id">User ID</label>
						<input id="user_id" name="user_id" type="text"/>
					</li>
					<li>
						<label for="password">Password</label>
						<input id="password" name="password" type="password" />
					</li>
				</ul>
				<div class="buttons">
					<button type="submit" id="login_button" name="login_button">login</button>
				</div>
				<?php if (isset($_GET['errorMsg']) && !empty($_GET['errorMsg'])): ?>
				
						<h1><?= $_GET['errorMsg']; ?></h1>
				
				<?php endif ?>
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