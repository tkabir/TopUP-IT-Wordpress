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
define('DB_NAME', 'wordpress_nsu');

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
define('AUTH_KEY',         '#V$au=YH(|P=v4[kcn_TQ2@iyE|l.Xn<kMzjNabx&Rynn*I_V011Ppu+>+ZK<Ayk');
define('SECURE_AUTH_KEY',  'YeeCG${?nb/4Vx#F8i9l@[U9nz2lc`|m%kVT5[lh T-{]TpAd%~t;($N^NP{-=.(');
define('LOGGED_IN_KEY',    'm2jDOU|gzEt+|`u-CP@3`|:+t0D}CY u[@:}2j[=&K=/>bd|QxKZ6&*UjZ?;X|j{');
define('NONCE_KEY',        'e%E`F5C#9,>$Wu]/YEu1e93E|m$h},1bXBY6/Ck`BPO]91:u{lr(r_30qm@R7+GF');
define('AUTH_SALT',        'er[GJRxTUy~|0J6&/-5 ,E+$s-|.=4__buo:;}5wi(++!DT@BuM2p<N`F#xnnvc9');
define('SECURE_AUTH_SALT', '-51e2KGs|3hEK9He7&nMi(4Yh~?V3y:|HUG^+~hj.MgZQ&u}oT-x`tzB>(4:0[b(');
define('LOGGED_IN_SALT',   '0CBI:^z/<XbKa-]Wh/|8U!QdBL,Sy>_+AQgp,Z>Adq>Pl:>5rf5S7oG3 AH0r]O%');
define('NONCE_SALT',       'HW)93nh8W|zmF*IFP.DYE>q|-Hl%9yO7gd#29O-m@AdCrA +Fn?zr2U+!Z-{UuIO');

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
