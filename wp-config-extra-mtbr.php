<?php

global $SITE_CONFIG;
global $SITE_NAME;
$SITE_NAME="mtbr";
$SITE_CONFIG = [
    'nginx_config' => [
       'template_file' => ABSPATH . 'wp-content/plugins/sparkle-site/mtbr-nginx.conf.template', 
       // The below file should be included in main nginx.conf. 
       'output_file' => ABSPATH . 'wp-content/plugins/sparkle-site/nginx-config/nginx.conf'
    ]
];
