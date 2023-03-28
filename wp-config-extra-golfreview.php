<?php

global $SITE_CONFIG;
global $SITE_NAME;
$SITE_NAME="golfreview";
$SITE_CONFIG = [
    'nginx_config' => [
       'template_file' => ABSPATH . 'wp-content/plugins/sparkle-site/golfreview-nginx.conf.template', 
       // The below file should be included in main nginx.conf. 
       'output_file' => ABSPATH . 'wp-content/plugins/sparkle-site/nginx-config/nginx.conf'
    ]
];
