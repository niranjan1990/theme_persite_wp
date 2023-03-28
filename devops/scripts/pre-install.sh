#!/bin/bash

/usr/bin/chef-solo -c /home/inv-deploy-user/chef-dev/solo.rb \
--legacy-mode \
-o "recipe[invenda_wp-venice::pre-install]" \
-E development
