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
define( 'DB_NAME', 'ecomsource' );

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
define( 'AUTH_KEY',         'Sg:v>X)|9[dZwi*a3mWpLIc=m>8q~NyB /6;EjrBV%`?5cowTl;{%~lZG{RXJOVJ' );
define( 'SECURE_AUTH_KEY',  'rid~}t:%-xDv#/*AH<tS)&YJn(O {snCr1nn|{:hpo3GpxQHtN*k(RL4+xvFIB6o' );
define( 'LOGGED_IN_KEY',    '~l0>.i P.m$&DI:GdfQ{ErBaV!JR1t`DY@SJrr#.# rz*y2xt+EnMM!H; h>~rF;' );
define( 'NONCE_KEY',        'yDCf;2}}gtq1JDe]r:kO_fXFS12|CGk!YD*n ;=dFa%[1%@|e?2RQ1!}FG.`V<,.' );
define( 'AUTH_SALT',        'If&MB{D9LS3v-6D6o{qfA$u^0@9-~ujsQ3{c?UBRmB!Ga~o!9cxDkE=Y.3YcVpNg' );
define( 'SECURE_AUTH_SALT', '=mHFDF[Ll!R[&AD%^6Cb=4slOF4 >Ay=){=]Vg^GDzij3iS3;K0u8yi4XCL;~H7q' );
define( 'LOGGED_IN_SALT',   'Te*#n|QOv9qb3(AplUL;U[:C1![>S3_5m^-&t))<`WLn!ixZor(T|9xO0L!5NI-M' );
define( 'NONCE_SALT',       'k7^&eqJY$LdP<0|oyzKQ^S/lx@#{[Y]pT!^^2z%uKL,rDR?,M3^9d:9:y#9<lHzF' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ecs_';

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
