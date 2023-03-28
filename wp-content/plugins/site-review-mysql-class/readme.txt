=== Plugin Name ===
golf-review-plugin-mysql

== Description ==

Displays the data from local mysql database into the Golfreview Theme.
Does not required MSSQL DB Once it has connect the mysql new db.

== Installation ==

1. Upload Dir 'golf-review-plugin-mysql' to the `/wp-content/plugins/` directory
2. To connect the new mysql db confirm the new mysql db 'golf_venice_new' on the Server (172.20.10.213).
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. If mysql server is remotely connected with production server, please change the mysql db connection setting from the plugin file (golfreview-class.php) at line (14-17).
