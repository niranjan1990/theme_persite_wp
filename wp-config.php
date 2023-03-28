<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

  define('COOKIE_DOMAIN', '.golfreview.com');
 
/** START AWS SES WP MAIL CONFIG **/

define( 'AWS_SES_WP_MAIL_REGION', 'us-east-1' );
define( 'AWS_SES_WP_MAIL_KEY', 'AKIAJXGLFE7IPFTW24HQ' );
define( 'AWS_SES_WP_MAIL_SECRET', '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' );


define('AWS_SES_WP_MAIL_USE_INSTANCE_PROFILE', true);

/** END AWS SES WP MAIL CONFIG **/ 
 
 
 
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'golf_wp_staging');

/** MySQL database username */
define('DB_USER', 'dbuser_gr_wp');

/** MySQL database password */
define('DB_PASSWORD', 'g4d3u8st');

/** MySQL hostname */
define('DB_HOST', 'db-stage-01.golfreview.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**   search variable  **/
			$url = explode( '/', $_SERVER['REQUEST_URI']);
			$url1 = explode( '.', $url[1]);
			
$age = array();
/**    search variable     **/

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '*Ti*i~_v)% ^RhKl#LJJ.q%4I1lE>vN%$z;w}4H/`TU>,iGsk|RL7C:$mD8|a^xx');
define('SECURE_AUTH_KEY',  '>0/Gr2#_7e?K-,nlqH+7;%hjy_GItG<d)mDufsI|FB_VE b}lC3|Lbt)9LQTV6ag');
define('LOGGED_IN_KEY',    '--aBG]~}]6nL0ch=&jU9?~)k`V1ABO|Kqo;zr_t75Bs3z6k&x#{LR,/>+phti=O3');
define('NONCE_KEY',        'g8Lh|{b[RX?-gu4)Ga8xUHVKk<.y)=Z8o(I.p:$wybM}h&f?.|73U<t}s4?cmwaS');
define('AUTH_SALT',        'r53k=[Jl`Ras@j]c<Po+:-!?]I[;J-Imr5Z$<qgWW*_Tg4her26^r]R OIvS7V<,');
define('SECURE_AUTH_SALT', '`/XiaKhyxy2B<]M)<.eoue[sa>P3I4XzD][ErqHK6|nv_?)?f`P5`(3awxq@hr[6');
define('LOGGED_IN_SALT',   'Rw&(~z^mJl+kku%`dCRmFh/Y$Lvs&O8ptBpmO8d .jAtU@p}HoMm&vX9SX1UWQay');
define('NONCE_SALT',       'DR}N6..eA-GgU2#/E$ZvuI}wjGR9+oC?zmB<p_5J^~Dn5Xi3@|LeuoMlF=NHa@yK');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
