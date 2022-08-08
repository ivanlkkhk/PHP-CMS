<?php 
	// This page is using for edit user_account.
	session_start();
	
	//Content Manager  only, if not forward to login page.
	if (!($_SESSION['valid'] && $_SESSION['user_type'] === 'C') ){
		header('Location: login.php');	
	}

	// Connection to database
    require('connect.php');

    // Retrieve cards information table .
    $cardsQuery = "SELECT c.*, l.code as level, l.order_by, cat.code as category FROM cards c, level l, category cat WHERE c.level_id = l.level_id AND c.category_id = cat.category_id AND c.card_id = :id LIMIT 1";

    // A PDO::Statement is prepared from the query.
    $cardsStatement = $db->prepare($cardsQuery);

    // Sanitize $_GET['id'] to ensure it's a characters.
    $id = $_GET['id'];

    if(!filter_var($id, FILTER_SANITIZE_NUMBER_INT)){
        // Redirect to the Card List page.
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'index.php';
        exit;
    }

    // Bind the :id parameter in the query to the sanitized
    $cardsStatement->bindValue('id', $id);
    $cardsStatement->execute();
    
    // Fetch the row selected by primary key id.
    $cardsRow = $cardsStatement->fetch();

    if ($cardsStatement->rowCount()==0) {
		// Redirect to the Card List page.
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'index.php';
        exit;
    }

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
    <title>Dragon Ball Z Dokkan Battle - Edit Card</title>
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
					<h2>Dragon Ball Z Dokkan Battle - Edit Card</h2>
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
							<?php	if ($cardsRow['level_id'] === $levelsRow['level_id']): ?>
										<option value="<?= $levelsRow['level_id'] ?>" selected > <?= $levelsRow['code'] ?></option>
							<?php 	else: ?>
										<option value="<?= $levelsRow['level_id'] ?>" > <?= $levelsRow['code'] ?></option>
							<?php 	endif ?>
							<?php endwhile ?>
						</select>
					</li>
					<li>
						<label for="category_id">Category:</label>
						<select id="category_id" name="category_id">
							<?php while($catsRow = $catStatement->fetch()):?>
							<?php	if ($cardsRow['category_id'] === $catsRow['category_id']): ?>
										<option value="<?= $catsRow['category_id'] ?>" selected > <?= $catsRow['code'] . " - " .$catsRow['description'] ?></option>
							<?php 	else: ?>
										<option value="<?= $catsRow['category_id'] ?>" > <?= $catsRow['code'] . " - " .$catsRow['description'] ?></option>
							<?php 	endif ?>
							<?php endwhile ?>
						</select>
					</li>
					<li>
						<label for="name">Name:</label>
						<input id="name" name="name" type="text" value="<?= $cardsRow['name']?>"/>
					</li>
					<li>
						<label for="description">Description:</label>
						<textarea id="description" name="description"><?= $cardsRow['description']?></textarea>
					</li>
					<li>
						<label for="hp">HP</label>
						<input id="hp" name="hp" type="number" min="1" value="<?= $cardsRow['hp'] ?>"/>
					</li>
					<li>
						<label for="atk">ATK</label>
						<input id="atk" name="atk" type="number" min="1" value="<?= $cardsRow['atk'] ?>"/>
					</li>
					<li>
						<label for="def">DEF</label>
						<input id="def" name="def" type="number" min="1" value="<?= $cardsRow['def'] ?>"/>
					</li>
					<li>
						<?php if(!empty($cardsRow['icon_path'])) : ?>
								<!--<label for="del_icon"> Delete Icon: </label>-->
								Delete Image:
								<input id="del_icon" name="del_icon" type="checkbox" />
								<br>
								<img src="<?= $cardsRow['icon_path'] ?>" width="100" alt="<?= $cardsRow['name']?>" >
						<?php else: ?>
								<label for='icon_path'>Icon path:</label>
								<div id='img_icon'>
								</div>
					            <input type='file' name='icon_filepath' id='icon_filepath' ?>
						<?php endif ?>
						<input id="icon_path" name="icon_path" type="hidden" value="<?= $cardsRow['icon_path']?>" />
			        </li>
			        <li>
			            <?php if(!empty($cardsRow['image_path'])) : ?>
								
								<!--<label for="del_image"> Delete Image: </label>-->
								Delete Image:
								<input id="del_image" name="del_image" type="checkbox" />
								<br>
								<img src="<?= $cardsRow['image_path'] ?>" width="200"  alt="<?= $cardsRow['name']?>" >
						<?php else: ?>
								<label for='image_path'>Image path:</label>
								<div id='img_image'>
								</div>
					            <input type='file' name='image_filepath' id='image_filepath' ?>
						<?php endif ?>
			            <input id="image_path" name="image_path" type="hidden" value="<?= $cardsRow['image_path']?>" />
			        </li>
				</ul>
				<div class="buttons">
					<button type="submit" name="command" value="update_card">Save</button>
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