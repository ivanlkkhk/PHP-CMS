<?php 
	// This page is using for edit user_account.
	session_start();
	
	//Content Manager  only, if not forward to login page.
	if (!($_SESSION['valid'] && $_SESSION['user_type'] === 'C') ){
		header('Location: login.php');	
	}

	// Connection to database
    require('connect.php');

    // Retrieve level master table.
    $levelQuery = "SELECT * FROM level WHERE active = 'Y' ORDER BY order_by";
    $levelStatement = $db->prepare($levelQuery);
    $levelStatement->execute();
    
    // Retrieve category master table.
    $catQuery = "SELECT * FROM category WHERE active = 'Y' ORDER BY code";
    $catStatement = $db->prepare($catQuery);
    $catStatement->execute();
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>Dragon Ball Z Dokkan Battle - Create Card</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.tiny.cloud/1/s1caa5uf3vmln7ifnobc498v23j66kum0rj1zjnc3szr7qqp/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
	<script>
    tinymce.init({
      selector: 'textarea',
      plugins: 'a11ychecker advcode casechange export formatpainter image editimage linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tableofcontents tinycomments tinymcespellchecker',
      toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter image editimage pageembed permanentpen table tableofcontents',
      toolbar_mode: 'floating',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
    });
  	</script>
</head>
<body>
	<header>
		<div id="heading">
			<div id="mainMenu">
				<h2>Dragon Ball Z Dokkan Battle - Create Card</h2>
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
		<form id="cardForm"
		      action="process_card_post.php" 
		      method="post"
		      enctype='multipart/form-data'>
			<fieldset id="card">
				<legend>Enter Card Information</legend>
				<input id="card_id" name="card_id" type="hidden" value="<?= $cardsRow['card_id']?>" />
				<ul>
					<li>
						<label for="level_id">Level:</label>
						<select id="level_id" name="level_id">
							<?php while($levelsRow = $levelStatement->fetch()):?>
								<option value="<?= $levelsRow['level_id'] ?>" > <?= $levelsRow['code'] ?></option>
							<?php endwhile ?>
						</select>
					</li>
					<li>
						<label for="category_id">Category:</label>
						<select id="category_id" name="category_id">
							<?php while($catsRow = $catStatement->fetch()):?>
								<option value="<?= $catsRow['category_id'] ?>" > <?= $catsRow['code'] . " - " .$catsRow['description'] ?></option>
							<?php endwhile ?>
						</select>
					</li>
					<li>
						<label for="name">Name:</label>
						<input id="name" name="name" type="text" />
					</li>
					<li>
						<label for="description">Description:</label>
						<!--<input id="description" name="description" type="text" />-->
						<textarea id="description" name="description"></textarea>
					</li>
					<li>
						<label for="hp">HP</label>
						<input id="hp" name="hp" type="number" min="1" />
					</li>
					<li>
						<label for="atk">ATK</label>
						<input id="atk" name="atk" type="number" min="1" />
					</li>
					<li>
						<label for="def">DEF</label>
						<input id="def" name="def" type="number" min="1" />
					</li>
					<li>
						<label for='icon_filepath'>Icon path:</label>
			            <input type='file' name='icon_filepath' id='icon_filepath' />
			            <input id="icon_path" name="icon_path" type="hidden"  />
			        </li>
			        <li>
						<label for='image_filepath'>Image path:</label>
			            <input type='file' name='image_filepath' id='image_filepath' />
			            <input id="image_path" name="image_path" type="hidden" />
			        </li>
				</ul>
				<div class="buttons">
					<button type="submit" name="command" value="create_card">Save</button>
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
	<script src="cards_event.js"></script>
</body>
</html> 