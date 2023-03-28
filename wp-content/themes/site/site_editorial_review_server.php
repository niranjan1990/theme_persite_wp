<?php

ob_start();
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
require $_SERVER['DOCUMENT_ROOT'] . '/wp-reviewconfig.php';
//mysql_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD);
//mysql_select_db(DB_RNAME);
require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

$keyword = strval($_POST['query']);
$search_param = "{$keyword}%";


// Report all PHP errors
error_reporting(-1);
$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));


if(isset($_REQUEST['query']))
{
//  echo 'query inside' .$_GET["query"];
//  $prefix = $_POST["query"];
  //echo $prefix;
  $query = $_REQUEST['query'];

  //$sqlquery = sprintf("SELECT product_name FROM products WHERE product_name LIKE '%s%%'", mysql_real_escape_string($prefix));
  $sqlquery = "SELECT productid, product_name FROM products WHERE product_name LIKE '%{$query}%' order by product_name";
  //echo $sqlquery;
  //'".$_GET['query']."'";
  $sql = mysqli_query($con, $sqlquery);
  //echo $sql;
   //$resultset = mysql_query($sql);
  // $row = mysqli_fetch_assoc($sql);
//  $ProductResult = [];
   while($result=mysqli_fetch_assoc($sql)){
     $ProductResult[] = array (
            'productid' => $result['productid'],
            'product_name' => $result['product_name'],
        );
    // $ProductResult[] = $result["product_name"];

   }
    echo json_encode($ProductResult);
   //echo $row->num_rows;

  /*  if($row)
    {
      $ProductResult[] = $row["product_name"];
      echo json_encode($ProductResult);
    }*/

	}


//mysql_close($con);
/*$sql->bind_param("s",$search_param);
$sql->execute();
$result = $sql->get_result();
echo $result->num_rows;
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
	$ProductResult[] = $row["product_name"];
  echo json_encode($ProductResult);
	}
	echo json_encode($ProductResult);
}
$con->close();
*/
?>
