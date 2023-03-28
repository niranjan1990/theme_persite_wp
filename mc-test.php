<?php
    
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
    
    // Get value
    $key = "cr.editorial.latestreviews";
    $content = $mc->get($key);
    
    if ($mc->getResultCode() == Memcached::RES_SUCCESS) {
        echo("memcached get success");
    } else {
        echo("memcached get ERROR - code: " . $mc->getResultCode());
    }
    
    echo("<hr/>");
    echo($content);
    
?>