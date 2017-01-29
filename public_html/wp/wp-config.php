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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'mjuice_travelo');

/** MySQL database username */
define('DB_USER', 'mjuice_travelo');

/** MySQL database password */
define('DB_PASSWORD', '8DSh4.M3[p');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'setzsovluezscnpmdba6chppsmfjmg7rzk7znlsi3ny10hdxg02ibeinxbpa4iwg');
define('SECURE_AUTH_KEY',  'imava3advs2gy2qdaek2ij6dhql78abgdo9ldrysusvwp2gfbivkhozv9iyloazt');
define('LOGGED_IN_KEY',    'mexwv3tqcrz8wvxb2li7xkq3vvqtjytvkvepxeg0qjvzmpq1sq1pulr2uzvt25ow');
define('NONCE_KEY',        'hedafc1v1dupoebgbmqjauah1xzbgrfinfwunpaldsgdxxvsrchvkykperskrvxu');
define('AUTH_SALT',        'v3l6tiliiqghghtl5mloe9xkjwykkjlqhrarp8s10ruvh1q0hoxdyxllhrokqdvg');
define('SECURE_AUTH_SALT', 'hjuuymgi1r4lqh41vnv9phckov6h8pwfjd2akwu4ipbmhxvj3jypaqb8ciobqcwc');
define('LOGGED_IN_SALT',   'fjt8glzcj53ytsgvn2i1hukucegujgeqxqm09nxjmyh2vzswzbwz565coy5yqo14');
define('NONCE_SALT',       'nm1uwdjfmm1p8sxfmonijal7wz6owqkhnh9dbfisylmlw71kductwapoqjxabfjv');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpcr_';

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

/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'travelo.tips');
define('PATH_CURRENT_SITE', '/wp/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
