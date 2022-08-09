<?php
	session_start();

	//System user onlly, if not forward to login page.
	if (!($_SESSION['valid'] && $_SESSION['user_type'] === 'S') ){
		header('Location: login.php');	
	}

	// Connection to database
    require('connect.php');

    if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
        $keyword = filter_input(INPUT_GET, 'keyword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $query = "SELECT user_id, name, type, modify_date, create_date, active FROM user WHERE name like '%" . $keyword . "%' ORDER BY user_id";
    }else{
	    // Retrieve all records from user table and order by user id.
	    $keyword = '';
	    $query = "SELECT user_id, name, type, modify_date, create_date, active FROM user ORDER BY user_id";
	}

    // A PDO::Statement is prepared from the query.
    $statement = $db->prepare($query);

    // Execution on the DB server is delayed until we execute().
    $statement->execute(); 

    // Prepare for pagination
	$results_per_page = 5;
    $rowCount = $statement->rowCount();
    
    if ($rowCount > $results_per_page) {
    	$no_of_page = ceil($rowCount/$results_per_page);
    }else{
    	$no_of_page = 1;
    }

	if (!isset ($_GET['page']) ) {  
        $page = 1;  
    } else {  
        $page = $_GET['page']; 
    }  

	$page_first_result = ($page-1) * $results_per_page;  

    // Retrieve the records require for current page.

	if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
        $keyword = filter_input(INPUT_GET, 'keyword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $query = "SELECT user_id, name, type, modify_date, create_date, active FROM user WHERE name like '%" . $keyword . "%' ORDER BY user_id LIMIT "  . $page_first_result . ',' . $results_per_page;  
    }else{
	    // Retrieve all records from user table and order by user id.
		$query = "SELECT user_id, name, type, modify_date, create_date, active FROM user ORDER BY user_id LIMIT "  . $page_first_result . ',' . $results_per_page;  
	}

    // A PDO::Statement is prepared from the query.
    $statement = $db->prepare($query);

    // Execution on the DB server is delayed until we execute().
    $statement->execute(); 
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
			<div id="image">
				<img class="headerImage" src="images/banner.jpg" alt="Dragon Ball Z Dokkan Battle">
			</div>
		</div>	
	</header>	
	<section>
		<div id="search">
			<?php if(isset($_SESSION['valid']) && ($_SESSION['valid'])): ?>
				<label for="keyword">Search Name</label>
				<input id="keyword" name="keyword" type="text" value=<?= $keyword ?>>
			<?php endif ?>
		</div>
		<div id="content">
			<?php if($statement->rowCount()==0): ?>
				<h2>No records<h2>
			<?php else: ?>
				<table>
					<thead>
						<tr>
							<td>User ID</td>
							<td>Name</td>
							<td>Type</td>
							<td>Modify Date</td>
							<td>Create Date</td>
							<td>Active</td>
							<td colspan="2"><a href="user_admin_create.php">Create</a></td>
						</tr>
					</thead>
					<?php while($row = $statement->fetch()): ?>
						<tr>
							<td ><?= $row['user_id'] ?></td>
							<td><?= $row['name'] ?></td>
							<?php 
								switch ($row['type']) {
									case 'C':
										$type = 'Content Manager';
										break;
									case 'S':
										$type = 'System Administrater';
										break;
									case 'R':
										$type = 'Registered User';
										break;
								}
							?>
							<td><?= $type ?></td>
							<td class='center'><?= $row['modify_date'] ?></td>
							<td class="center"><?= $row['create_date'] ?></td>
							<td class="center"><?= $row['active'] ?></td>
							<td class="center"><a href="user_admin_edit.php?id=<?= $row['user_id']?>">Edit</a></td>
							<td class="center"><a href="javascript:del_user('<?= $row['user_id']?>');">Del</a></td>
						</tr>
					<?php endwhile ?>
					
					<?php if($no_of_page > 1): ?>
						<tr>
							<td colspan="8">
								<div class="pagination">
								<?php if($keyword != ''): ?>
									<?php if($page>=2): ?>
											<a href='user_admin.php?page=<?= $page-1 ?>&keyword=<?= $keyword ?>'>  Prev </a>
									<?php endif ?>
									<?php for($pageIdx = 1; $pageIdx<= $no_of_page; $pageIdx++): ?>  
										<?php if ($page == $pageIdx): ?>
											<a href = "user_admin.php?page=<?= $pageIdx ?>&keyword=<?= $keyword ?>" class="active"><?= $pageIdx ?></a>  
										<?php else: ?>
									    	<a href = "user_admin.php?page=<?= $pageIdx ?>&keyword=<?= $keyword ?>"><?= $pageIdx ?></a>  
									    <?php endif ?>
									<?php endfor ?>
									<?php if($page<$no_of_page): ?>
		            					<a href='user_admin.php?page=<?= $page+1 ?>&keyword=<?= $keyword ?>'>  Next </a>
		            				<?php endif ?>
	            				<?php else: ?>
									<?php if($page>=2): ?>
											<a href='user_admin.php?page=<?= $page-1 ?>'>  Prev </a>
										<?php endif ?>
									<?php for($pageIdx = 1; $pageIdx<= $no_of_page; $pageIdx++): ?>  
										<?php if ($page == $pageIdx): ?>
											<a href = "user_admin.php?page=<?= $pageIdx ?>" class="active"><?= $pageIdx ?></a>  
										<?php else: ?>
									    	<a href = "user_admin.php?page=<?= $pageIdx ?>"><?= $pageIdx ?></a>  
									    <?php endif ?>
									<?php endfor ?>
									<?php if($page<$no_of_page): ?>
		            					<a href='user_admin.php?page=<?= $page+1 ?>'>  Next </a>
		            				<?php endif ?>
		            			<?php endif ?>
	            				</div>
							</td>
						</tr>
					<?php endif ?>
				</table>
			<?php endif ?>
		</div>
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
	<script src="user_admin.js"></script>
</body>
</html> 