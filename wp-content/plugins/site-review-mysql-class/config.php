<?php
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
	/* full path to plugin folder */
	$GRdb['path'] = '/site/wp-content/plugins/site-review-mysql-class/';
	// url for CDN images //
	
	define('GR_IMG', $PRODUCTIMAGE_CDNDOMAIN.'/'.$PRODUCTIMAGE_S3FOLDER.'/images/products/');
	define('GR_CIMG', $PRODUCTIMAGE_CDNDOMAIN.'/'.$PRODUCTIMAGE_S3FOLDER.'/images/products/');
		
	
	/**
	* No further changes required
	* Include required functions and classes
	*/
 
	require_once 'site-review-class.php';
	require_once 'site-review-permalinks.php';
	
?>
