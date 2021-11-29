<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'woo_test' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '^Y]@q@D ]GNcmV0M-rjC8!Y`~7i$OQNdO[3hN#&=E<3tVsz0+6FrMxell:-#]<$v' );
define( 'SECURE_AUTH_KEY',  'SK($Vv-Y8BV@HWN7>YG@Cb)}VufYl/5-<uLV2Ah.L/JhH0eY}?<;G?[UBsSHZ~Vc' );
define( 'LOGGED_IN_KEY',    'Jj6]huCw_(,U?X|Tr1k>j%Rs0(k40yd.alq^Bkg8KlWN=aXGt_k_~co_/(P9hm?]' );
define( 'NONCE_KEY',        '$ 63)B/lTx/[`r+nL2#RTQ~X-=y,q9)3;gCimi~!*.;LC^#`dy+@9:)l3},,}Y80' );
define( 'AUTH_SALT',        'mgY_l>|?2.FITR]%u#ZfCuVX@W?()LYQ%`I=3AlB/v W^0DjWd:q3.AL%eS=:0-{' );
define( 'SECURE_AUTH_SALT', 'J7dz,kvxN&unGT4L6-=~.oH_Ow2/ksnY):#~N)E26|1XA-)=Yl}(UYjb+!/kl> ]' );
define( 'LOGGED_IN_SALT',   'UI=A5I0g|AIf(v751HLU+=+6{2Yp8r{Z--$z`x.:O!-/H_edqBIJ0@tUd >6N3Fh' );
define( 'NONCE_SALT',       'CkcdP:i&&T,eH%yE$|=[?V8|H3-PfyWb.^6)jeK$[su;);>n:T%U[GJRg=sI_-Ib' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'mv_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
