<?php

$con = mysqli_connect("localhost","root","","forum") or die("Some error occurred during connection " . mysqli_error($con));  
$result=mysqli_query($con,"select * from curl1");

    while ($row = $result->fetch_assoc()) 
	{
		file_get_contents($row['newurl']);
		$result1=mysqli_query($con,"update curl1 set result='".$http_response_header[0]."' where id='".$row['id']."'");
    }

?>