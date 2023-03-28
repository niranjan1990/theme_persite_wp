<?php	
	
	function getDirectory( $path = '.', $fname, $level = 0 ) {		
		
		$ignore = array( 'cgi-bin', '.', '..' );
       	$dh = @opendir( $path );
		while( false !== ( $file = readdir( $dh ) ) ){			
			if( !in_array( $file, $ignore ) ){
				if( $file == $fname ) { return $file; }
			}
		}
		closedir( $dh );
    }


	if ( isset($_POST['action'])) {
	
		$action = $_POST['action'];
		switch ( $action ) {
	
			case 'image-editor' :
				
				require_once('../../../wp-load.php');
				global $wpdb;
				
				$postid 	= 	intval($_POST['postid']);
				$imgpath 	= 	$_POST['path'];
				$imgname 	= 	$_POST['imgname'];
				
				$ran2 = rand(1,5689990);
				$ran3 = rand(1,5689990);				
				$upload_dir = wp_upload_dir();				
				$newfile = $upload_dir['path'].'/'.$imgname;
				//before copy image validated
				
				if(getDirectory( $upload_dir['path'], $imgname)) {
					
					$title		=	explode(".", getDirectory( $upload_dir['path'], $imgname));
					$title_name	= 	$title[0].'-'.rand(1,999);
					$ext		=	$title[1];
					
				}
				else {
				
					$title		=	explode(".", $imgname);
					$title_name	= 	$title[0];
					$ext		=	$title[1];
				}
				
				system('wget '.$imgpath.' -O '.$newfile, $out);	
				$uri = ltrim($upload_dir['subdir'], '/');
				
				$resizeImagePath = $newfile;
				$size	= getimagesize( $resizeImagePath );				
				$src	= imagecreatefromjpeg($resizeImagePath);
				
				list($width,$height) = getimagesize($resizeImagePath);
				$filename = $upload_dir['path'].'/'.$title_name.'-150x150.'.$ext;
				$newwidth=150;
				$newheight =($height/$width)*$newwidth;
				$tmp=imagecreatetruecolor($newwidth,$newheight);	
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				imagejpeg($tmp,$filename,100);
				imagedestroy($src);
				imagedestroy($tmp);				
				
				$serialize = array (	
					'width' => 	$size[0], 
					'height' => $size[1],
					'hwstring_small' => 'height=96 width=96',
					'file' => $uri."/".$imgname,
					'sizes' => array (	
						'thumbnail' => 	array (	
							'file' => $title_name.'-150x150.'.$ext,
							'width' => 	150,
							'height' => 150, ),				
					),
					'image_meta' => array (
						'aperture' => 0,
						'credit' => '',
						'camera' => '',
						'caption' => '',
						'created_timestamp' => 0,
						'copyright' => '',
						'focal_length' => 0,
						'iso' => 0,
						'shutter_speed' => 0,
						'title' => '',
					),
				);
				
				$wp_attachment_metadata_serial = serialize($serialize);				
				$prefix = 'wp';
				$sql = "INSERT INTO ".$prefix."_posts VALUES ( '$postid', 1, now(), now(), '', '".$title_name."', '', 'inherit', 'open', 'open', '', 'example', '', '', now(), now(), '', 0, '$newfile', 0, 'attachment', 'image/jpeg', 0, '')";
				$wpdb->query($sql);
				$sqlMeta1 = "INSERT INTO ".$prefix."_postmeta VALUES ('$ran2','$postid','_wp_attachment_metadata','$wp_attachment_metadata_serial')";
				$wpdb->query($sqlMeta1);
				$sqlMeta2 = "INSERT INTO ".$prefix."_postmeta VALUES ('$ran3','$postid','_wp_attached_file','".$uri."/".$imgname."')";
				$wpdb->query($sqlMeta2);
				
			break;		
		}
	}
?>