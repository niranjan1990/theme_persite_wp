<?php
//curl.php?domain=http://stage-www.mtbr.com&input=redirectlist.txt&output=redirectout.txt
function curlgetinfo($url)
{
		$timeout = 0;
		$ch = curl_init();
		// set cURL options
		$opts = array(CURLOPT_RETURNTRANSFER => true, // do not output to browser
					CURLOPT_URL => $url,       // set URL
					CURLOPT_NOBODY => true,         // do a HEAD request only
					CURLOPT_TIMEOUT => $timeout);   // set timeout
		curl_setopt_array($ch, $opts);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_exec($ch); // do it!
$redir = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);



		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE); // find HTTP status
	$info = curl_getinfo($ch);
		curl_close($ch); // close handle


		return $headers = $url."		".$redir."		".$status."		".$info['total_time']."Secs"; //or return $status;

}

//curl.php?domain=http://stage-www.mtbr.com&input=redirectlist.txt&output=redirectout.txt


if(isset($argv[1]))
{
	$domain = $argv[1]; //http://stage-www.mtbr.com
	$inputfile = $argv[2]; //redirectlist.txt
	$outputfile = $argv[3]; //redirectout.txt
}
else if(isset($_GET['domain']))
{
	$domain = $_GET['domain']; //http://stage-www.mtbr.com
	$inputfile = $_GET['input']; //redirectlist.txt
	$outputfile = $_GET['output']; //redirectout.txt
}
else
{
	$domain = "http://stage-www.mtbr.com"; //http://stage-www.mtbr.com
	$inputfile = "redirectlist.txt"; //redirectlist.txt
	$outputfile = "redirectout.txt"; //redirectout.txt
	
}
				$file =  @file_get_contents($inputfile);
				if($file) 
				{
					
					$url_array = explode("\r\n",trim($file));
					if($url_array) 
					{
						
						$output = "";
						for($i=0;$i<count($url_array);$i++) 
						{
							$count = $i+1;
							if ($count % 100 == 0) 
							{
								echo "Processed Urls : ( ".$count." )\r\n";
							}
							$output .= curlgetinfo($domain.$url_array[$i])."\r\n";
						}					
						
						//echo $output;
						$fh = fopen($outputfile, 'w') or die("Can't find output file: ");
						fwrite($fh, $output);
						fclose($fh);
						
						echo 'URL redirects logged.(Total Urls Processed: '.$count.')';
					} 
					else 
					{
						
						echo 'Input file empty: '.$input;
					}
				}
				else {
					
					echo 'No input file found:'.$input;
				}
				die();


?>
