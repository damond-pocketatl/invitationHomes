<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/var/www/html/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'invhomes_prod');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'sdkjU87*kss');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'R7P-.5#$U:{;0 uNK}[/ `HNj+h-`bOaoZ4I!;tOfs+JA<Hm]q!AR6XS)R3ij4<2');
define('SECURE_AUTH_KEY',  ')Q97w>IwDw`|dZ#F,ZT`p;v*#5l;`hc}cJ4W=-s~0!Ws>sjEhE=M?c=9o6>DM;JX');
define('LOGGED_IN_KEY',    'P]+Wn5X%Rxgm/u0f!?blFY+3~HgbmU!rU-iADq^fiqQ]5GP67Y2CD})[k/Z,sN2P');
define('NONCE_KEY',        '-?=:x-H$wqTM4HFm9`zs& wP%vElIJ=dgiGnd7ZVpA;L|[G/-QKCw)RQ^8Q#+Z|V');
define('AUTH_SALT',        't#2R$[N5.$ZWP_Ryji(=]kiyFs-Vuxe.AQkGQ{4)X>s]pI5)duj?NSCbaHY._G}g');
define('SECURE_AUTH_SALT', 'ZE4<4(pSDBvNyOph($Flj;#i1Qsj;FEh,M(5X|Ym^?oluw-`-S^XA;gRy<Fao C4');
define('LOGGED_IN_SALT',   'EWQ.l5(,L&-~.4N~&!tRP@HJjFsjc] gC^$q+=;SCWDn!Qd1dvA-qraX4%e=y08j');
define('NONCE_SALT',       '+oi%($3GJRM``Oxi+O:+wuNBD]y&}kK)t4?eq!4k[W]CAvQ*|uN7+;vg5hqPQD3d');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

// Enable the WordPress Object Cache:
define('ENABLE_CACHE', true);