<?php
	session_start();

	if (!isset($_SESSION['user_id'])) {
		$_SESSION['user_id'] = "";
		$_SESSION['user_type'] = "";
		
	}

	if (!isset($_SESSION['valid'])) {
		$_SESSION['valid'] = "";
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>Dragon Ball Z Dokkan Battle</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>



	<header>
		<div id="heading">
				<div id="mainMenu">
					<h1><a href="#" >Dragon Ball Z Dokkan Battle</a></h1>
					<nav>
						<ul> 
							<li><a href="index.php">Home</a></li>
							<li><a href="news.php">News</a></li>
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
			<div id="image">
				<img class="headerImage" src="images/banner.jpg" alt="Dragon Ball Z Dokkan Battle">
			</div>
		</div>	
	</header>	
	<section>
		<div id="sorting">
			<?php if(isset($_SESSION['valid']) && ($_SESSION['valid'])): ?>
				<label for="sort">Sort</label>
				<select id="sort" name="sort">
					<option value="LVLCATN">Level + Category + Name</option>
					<option value="CATLVLN">Category + Level + Name</option>
					<option value="NAME">Name</option>
					<option value="HP">HP</option>
					<option value="ATK">ATK</option>
					<option value="DEF">DEF</option>
				</select>
			<?php endif ?>
		</div>
		<div id="search">
			<?php if(isset($_SESSION['valid']) && ($_SESSION['valid'])): ?>
				<label for="keyword">Search Name</label>
				<input id="keyword" name="keyword" type="text"/>
			<?php endif ?>
		</div>
		<div id="content"> 

		</div>
		<footer>
			<div id="footer">
            <div id="footerMenu">
	            <nav>
								<ul> 
									<li><a href="index.php">Home</a></li>
									<li><a href="news.php">News</a></li>
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
	<script>
	    var user_id = "<?php echo $_SESSION['user_id'] ?>";
	    var user_type = "<?php echo $_SESSION['user_type'] ?>";
	    var valid = "<?php echo $_SESSION['valid'] ?>";
	</script>
	<script src="cards.js"></script>

</body>
</html> 