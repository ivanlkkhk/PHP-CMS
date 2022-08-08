<?php 
	// This page is using for edit user_account.
	session_start();
	
	//System user onlly, if not forward to login page.
	if (!($_SESSION['valid'] && $_SESSION['user_type'] === 'S') ){
		header('Location: login.php');	
	}

	// Connection to database
    require('connect.php');

    // Retrieve all records from tweets table and order in descending order so the latest transaction will be listed on the top.
    $query = "SELECT user_id, name, type, modify_date, create_date, active FROM user WHERE user_id = :id LIMIT 1";

    // A PDO::Statement is prepared from the query.
    $statement = $db->prepare($query);

    // Sanitize $_GET['id'] to ensure it's a characters.
    $id = $_GET['id'];

    //echo $id;

    if(!filter_var($id, FILTER_SANITIZE_FULL_SPECIAL_CHARS)){
        // Redirect to the User Admin page.
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'user_admin.php';
        //header("Location: http://$host$uri/$extra");
        exit;
    }

    // Bind the :id parameter in the query to the sanitized
    $statement->bindValue('id', $id);
    $statement->execute();
    
    // Fetch the row selected by primary key id.
    $row = $statement->fetch();

    if ($statement->rowCount()==0) {
		// Redirect to the User Admin page.
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'user_admin.php';
        //header("Location: http://$host$uri/$extra");
        exit;
    }
    //echo $id;
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
					<nav>
						<ul> 
							<li><a href="index.php">Home</a></li>
							<?php if(isset($_SESSION['valid']) && ($_SESSION['valid'] && $_SESSION['user_type'] === 'S')): ?>
								<li><a href="user_admin.php">User Admin</a></li>
							<?php endif ?>
							<?php if(isset($_SESSION['valid']) && $_SESSION['valid']): ?>
								<li><a href="logout.php">Logout</a></li>
							<?php else: ?>
								<li><a href="login.php">Login</a></li>
							<?php endif ?>
						</ul>
					</nav>
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
						<input id="user_id" name="user_id" type="text" readonly="readonly" value="<?= $row['user_id']?>"/>
					</li>
					<li>
						<label for="name">Name</label>
						<input id="name" name="name" type="text" value="<?= $row['name']?>"/>
					</li>
					<li>
						<label for="userType">Type</label>
						<select id="userType" name="userType">
							<?php $selected = ($row['type'] === 'S')?"selected":" " ?>
							<option value="S" <?= $selected ?> >System Administrator</option>
							<?php $selected = ($row['type'] === 'C')?"selected":" " ?>
							<option value="C" <?= $selected ?> >Content Manager</option>
							<?php $selected = ($row['type'] === 'R')?"selected":" " ?>
							<option value="R" <?= $selected ?> >Registered User</option>
						</select>
					</li>
					<li>
						<label for="password">Password</label>
						<input id="password" name="password" type="password" />
					</li>
					<li>
						<label for="active">Active</label>
						<?php $checked = ($row['active'] === 'Y')?"checked":" " ?>
						<input id="active" name="active" type="checkbox" <?= $checked ?>/>
					</li>
				</ul>
				<div class="buttons">
					<button type="submit" name="command" value="update_user">Save</button>
				</div>
			</fieldset>
		</form>
		<footer>
			<div id="footer">
				<div id="footerMenu">
	            	<nav>
						<ul> 
							<li><a href="index.php">Home</a></li>
							<?php if(isset($_SESSION['valid']) && ($_SESSION['valid'] && $_SESSION['user_type'] === 'S')): ?>
								<li><a href="user_admin.php">User Admin</a></li>
							<?php endif ?>
							<?php if(isset($_SESSION['valid']) && $_SESSION['valid']): ?>
								<li><a href="logout.php">Logout</a></li>
							<?php else: ?>
								<li><a href="login.php">Login</a></li>
							<?php endif ?>
						</ul>
	            	</nav>
	            </div>
                <p>&copy; 2022 created by Kwok Keung Lai.</p>
			</div>
		</footer>
	</section>
</body>
</html> 