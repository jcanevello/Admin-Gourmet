<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'gourmet');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'r)$TM0*.P(>*u9Q%3&qSan<ta8hqXs@xex:gf{RDwm_4-~*O.Sww+|YA$Ky}DM.=');
define('SECURE_AUTH_KEY',  'VlR|oUrEmTPf4E%m`(g1{4;C t`e|*g|F,C4_ykM7jt:C||uE8s;Ya`ZUxXw_r1;');
define('LOGGED_IN_KEY',    'cj[+]K-|#GBC%*Q+V(f+Xep@vrRm[Gn)dA))G}CS4H/n_n_:U7+<C[*B_R{i-_wN');
define('NONCE_KEY',        'SRSffIE{46]Mlxu^8X2PsTe@$#yV1h,E9~>H6%q8JWiQVq>=aFEck)1n9~6X.%F4');
define('AUTH_SALT',        'F2.2N5+-MhG7h[ve|++H]Rm|0]0dYGb2-$&]U.*<f;c{&9=/wS=`((DmE]1-9R:j');
define('SECURE_AUTH_SALT', 'P%n J;qmow;GsTvSs4Z1A+J+5=zjR|@=,)r33tdW/-1;Z=;fl-y-|/g*tM_W@e91');
define('LOGGED_IN_SALT',   '+sx bnZ358YM^1]+baxC9uSjjvH{81!zYvofp7I?RC1`4ih:y<u7Ife7]_(Sc}?5');
define('NONCE_SALT',       'Yp*b^FAt*Z)+j0DED78O7RA@-CNr#m@58!$J2F$KAj,n[i%Km|ia39, qlm_E.Y+');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'g_';

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
