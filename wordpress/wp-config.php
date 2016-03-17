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
define('DB_NAME', 'wordpress_home');

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
define('AUTH_KEY',         'fz#=I^+V3-<11J@/C2eB+{eSKHVRtgnev`rr)oS-K4{>Ky)v3c|-GMYh)M,-C!W)');
define('SECURE_AUTH_KEY',  'xjmUCx*F^}zpWMVS<)8!9Nv[^Rtf;_0!zxU}md+N+PQRc@+I3SAY)Z7eVo(k~1A8');
define('LOGGED_IN_KEY',    'ZlzvS0GEOv,Pt8,[ul: wn}8uhipRR+EjAkO-P7{ HEup4B>A-k*x}!16gn/mV!w');
define('NONCE_KEY',        '~Ov$r.KtvW8s[Rtt]C^9y~6|<-#/#go{BuqB*%dXUdiA=gQjSIkTGn~s=wg3Eh@n');
define('AUTH_SALT',        'f:HBf|@y#U;u1G-$2iml=}mrG;xCsz-xo*_#}|)*qs_GP#.VTo^wT^)k.Fn~v8o7');
define('SECURE_AUTH_SALT', '_c|xQZ5FX5L(}l|X1cxn/W}3c~u)kZV>z@{t}od-{`;UKf<TyH)ZB2y.tnV.)2v/');
define('LOGGED_IN_SALT',   'ujPI|?zoN8zR7bdn|c*u:wB-> 9;v:,cMqTvP+CO^$p7-Fp-V@pp,SSmSO`b&NvC');
define('NONCE_SALT',       '( +g{|Sh0Y&q`$C[?`jnif#bneAL7Hs,x69/pgogwk#_!mn&KDy_qC^;-,sHQG>c');

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
