<?php

class ReviewInsertor {

	var $reviewFileMaxAgeInSeconds; 
    var $tempDir;
	var $reviewFilePathUrl;
	var $loadJquery;

	// Memcache Client
	var $mc;

	function __construct($reviewFilePathUrl, $reviewFileMaxAgeInSeconds, $tempDir, $loadJquery) {
		// All but reviewFilePathUrl are unused params (carry over from VB/forums related)
		// Left here to maintain method signature until updated 
		$this->reviewFilePathUrl = $reviewFilePathUrl;
        $this->reviewFileMaxAgeInSeconds = $reviewFileMaxAgeInSeconds;
        $this->tempDir = $tempDir;
        $this->loadJquery = $loadJquery; // value should be 'yes' or anything else.

        // Create MC cliet
        $this->mc = $this->createMemcacheClient();

	}

	function getReviews() {

		$content = '<!-- unavailable -->';

		// First attempt to load content from memcache
		$key = $this->buildMemcacheKey();
    	$mcContent = $this->mc->get($key);

    	// Check MC op status
    	if ($this->mc->getResultCode() != Memcached::RES_SUCCESS) {
    		error_log("ReviewInsertor - memcached get ERROR - code: " . $this->mc->getResultCode());
    		unset($mcContent);
    	}

    	// Check for valid contnet
    	if (isset($mcContent) && strlen($mcContent) > 1) {
    		$content = '<!-- loaded from mc --> ' . $mcContent;
    	} else {
			// Attempt to load content from static html
    		try {
				$httpContent = file_get_contents($this->reviewFilePathUrl);
				if ($httpContent === false) {
					error_log("ReviewInsertor - ERROR - file_get_contents returned false for " . $this->reviewFilePathUrl);
				} else {
					$content = '<!-- loaded from http --> ' . $httpContent;
				}
			} catch (Exception $e) {
				error_log("ReviewInsertor - ERROR - file_get_contents threw exception: " . $e . " / for " . $this->reviewFilePathUrl);
			}
    	}

		return '<!-- review articles widget starts 0326 --> ' . $content . ' <!-- review articles widget ends 0326 -->';

	}
	
	function createMemcacheClient() {
		// Build MC Server Config Array
		$mcServers = array();
		$configMemcacheListStr = session_save_path(); // Server list is configred via php.ini in deployment
		$configMemcacheList = explode(",", $configMemcacheListStr);
		foreach($configMemcacheList as $configMcServer) {
			$mcConfig = explode(":", $configMcServer);
			array_push($mcServers, $mcConfig);
    	}
    	// Create MC Client
		$mc = new Memcached();
		$mc->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
		$mc->addServers($mcServers);

		return $mc;
	}
	
	function buildMemcacheKey() {
		// Base Key
		$key = "cr.editorial.latestreviews-";
		
		// Get WP site URL
		$siteUrl = get_site_url();
		$siteHost = parse_url($siteUrl, PHP_URL_HOST);
		// Get domain name
		$siteHostArr = explode(".", $siteHost);
		$domTldIdx = count($siteHostArr) - 1;
		$domNameIdx = $domTldIdx - 1;
		$domainName = $siteHostArr[$domNameIdx] . "." . $siteHostArr[$domTldIdx];

		// Add domain to base key
		$key .= $domainName;

		return $key;
	}

}
