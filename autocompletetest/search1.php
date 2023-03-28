<?php
include('../wp-reviewconfig.php');



//print_r($_GET);
 
if (isset($_GET['term'])){
	$return_arr = array();

	try {
	    $conn = new PDO("mysql:host=".DB_RHOST.";port=3306;dbname=".DB_RNAME, DB_RUSER, DB_RPASSWORD);
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    
	    $stmt = $conn->prepare('SELECT * FROM search WHERE search_term LIKE :term and category_path like "%golf-cl%" limit 10');
	    $stmt->execute(array('term' => '%'.$_GET['term'].'%'));
	    
	    while($row = $stmt->fetch()) {
	        $return_arr[] =  $row['display_term'];
	    }

	} catch(PDOException $e) {
	    echo 'ERROR: ' . $e->getMessage();
	}


    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
	//echo "hi";
}


?>