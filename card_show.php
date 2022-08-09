<?php 
	// This page is using for view signle card.
	session_start();
	
	// Connection to database
    require('connect.php');
    require('CaptchasDotNet.php');

    // Retrieve all records from cards table .
    $cardsQuery = "SELECT c.*, l.code as level, l.order_by, cat.code as category FROM cards c, level l, category cat WHERE c.level_id = l.level_id AND c.category_id = cat.category_id AND c.card_id = :id LIMIT 1";

    // A PDO::Statement is prepared from the query.
    $cardsStatement = $db->prepare($cardsQuery);

    // Sanitize $_GET['id'] to ensure it's a characters.
    $id = $_GET['id'];

    if(!filter_var($id, FILTER_VALIDATE_INT)){
        // Redirect to the Card List page.
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'index.php';
        header("Location: http://$host$uri/$extra");
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
        header("Location: http://$host$uri/$extra");
        exit;
    }

    //Retrieve comments.
    $commentQuery = "SELECT * FROM card_comment WHERE card_id = :id ORDER BY create_date DESC";
	
	// A PDO::Statement is prepared from the query.
    $commentStatement = $db->prepare($commentQuery);

    // Bind the :id parameter in the query to the sanitized
    $commentStatement->bindValue('id', $id);
    $commentStatement->execute();
	
	// Fetch the row selected by primary key id.
	$commentRow = $cardsStatement->fetch();

	$captchas = new CaptchasDotNet ('demo', 'secret', '/captcha','3600', 'abcdefghkmnopqrstuvwxyz','6', '240','80','000088');

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>Dragon Ball Z Dokkan Battle - View Card</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>

	<header>
		<div id="heading">
				<div id="mainMenu">
					<h2>Dragon Ball Z Dokkan Battle - View Card</h2>
				</div>
		</div>	
	</header>	
	<section>
		<form id="card"
			action="process_comment_post.php" 
		    method="post">
			<table>
				
				<?php if (!empty($cardsRow['image_path'])): ?>
					<tr>	
						<td rowspan="8">
							<img src="<?=$cardsRow['image_path'] ?>" alt="<?= $cardsRow['name'] ?> " width=300>
						</td>
						<td colspan="2">
							<h4>Level:</h4>
						</td>
						<td colspan="2">
							<h4>Category:</h4>
						</td>
					</tr>
				<?php endif ?>

					<tr>
						<td colspan="2">
							<?= $cardsRow['level'] ?>
						</td>

						<td colspan="2">
							<?= $cardsRow['category'] ?>
						</td>
					</tr>

					<tr>
						<td colspan="4">
							<h4>Character Name:</h4>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<?= $cardsRow['name'] ?>
						</td>
					</tr>

					<tr>
						<td colspan="4">
							<h4>Description:</h4>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<?php echo html_entity_decode($cardsRow['description']) ?>
						</td>
					</tr>

					<tr>
						<td><h4>HP</h4></td>
						<td><h4>ATK</h4></td>
						<td><h4>DEF</h4></td>
						<td></td>
					</tr>
					<tr>
						<td><?= $cardsRow['hp'] ?></td>
						<td><?= $cardsRow['atk'] ?></td>
						<td><?= $cardsRow['def'] ?></td>
						<td></td>
					</tr>
			</table>
		<?php if(isset($_SESSION['valid']) && ($_SESSION['valid'])): ?>				
			<label for='commentText'> Comment: </label>
			<br>
			<textarea id='commentText' name='comment'></textarea>
			<input id='card_id' name='card_id' type='hidden' value= <?= $cardsRow['card_id']?>>
			<input type="hidden" name="random" value="<?= $captchas->random () ?>" />
 			The CAPTCHA password:
			<input name="password" size="6" />
			 <?= $captchas->image () ?> <a href="javascript:captchas_image_reload('captchas.net')">Reload Image</a>
			<div class="buttons">
				<button type="submit" name="command" value="update_comment">Comment</button>
			</div>
		<?php endif ?>

		</form>

		<?php if ($commentStatement->rowCount()>0): ?>
			<div id='comment'>
				<h2>Comment of this card</h2>
				<?php while($row = $commentStatement->fetch()): ?>

					<hr>
					<div id="comment-<?= $row['card_comment_id'] ?>" class="comment-post" >
						<h4 class='comment-id'>Comment ID#: <?=$row['card_comment_id'] ?></h4>
						<small class='comment-small'>Comment by: <?= $row['user_id'] ?> | Create date: <?= $row['create_date'] ?></small>
					</div>

					<p class='comment-content'><?= $row['comment'] ?></p>
				<?php endwhile?>
			</div>
		<?php endif ?>

		<footer>
			<div id="footer">
                <p>&copy; 2022 created by Kwok Keung Lai.</p>
			</div>
		</footer>
	</section>
</body>
</html> 