<?php
	// Connection to database
    require('connect.php');

    $sorting = $_GET['sorting'];

    switch ($sorting) {
        case 'LVLCATN': // Sort by Level + Category + Name
            $sortQuery = "ORDER BY l.order_by DESC, cat.code, name";
            break;
        case 'CATLVLN': // Sort by Category + Level + Name
            $sortQuery = "ORDER BY cat.code, l.order_by DESC, name";
            break;
        case 'NAME': // Sort by Name
            $sortQuery = "ORDER BY name";
            break;
        case 'HP': // Sort by HP
            $sortQuery = "ORDER BY HP";
            break;
        case 'ATK': // Sort by ATK
            $sortQuery = "ORDER BY ATK";
            break;
        case 'DEF': // Sort by DEF
            $sortQuery = "ORDER BY DEF";
            break;
        default: // Sort by Level + Category + Name
            $sortQuery = "ORDER BY l.order_by DESC, cat.code, name";
            break;
    }
    
    if(isset($_GET['search']) && !empty($_GET['search']) )
    {
        $keyword = $_GET['search'];

        $query = "SELECT c.*, l.code as level, l.order_by, cat.code as category FROM cards c, level l, category cat WHERE c.level_id = l.level_id AND c.category_id = cat.category_id AND name like '%" . $keyword . "%' " . $sortQuery;
    }else{
        // Retrieve all records from cards table and order in descending order so the latest transaction will be listed on the top.
        $query = "SELECT c.*, l.code as level, l.order_by, cat.code as category FROM cards c, level l, category cat WHERE c.level_id = l.level_id AND c.category_id = cat.category_id " . $sortQuery;
    }
        	
    // A PDO::Statement is prepared from the query.
    $statement = $db->prepare($query);

    // Execution on the DB server is delayed until we execute().
    $statement->execute(); 
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $json = json_encode($result);

    //echo $query;
    echo $json;
?>