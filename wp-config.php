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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '!Sc8PI%J!n!|x,H.~(}w}%dfM]Iq^m]U^T%]e(d0T:5#w.m(fj1xn*M7!!Q!M:Y-' );
define( 'SECURE_AUTH_KEY',  'l8g%R@ n[sQ8{JXh~F&lU<nXV:6lcTD$GuyP86,N]v8zB*D[Lt]2hyQ7|3@vy*_o' );
define( 'LOGGED_IN_KEY',    'G(Tri:Y0c:I+1&rF[&g23:b&M_xjh`8gYA=i|GqNK%<a]:gn~.JSM8` W]*+s/e%' );
define( 'NONCE_KEY',        'VymE8$s?_ 1=0)yLZ_211XparP=+* WC*oP;e^mC5R|L2mKfMHZ)L]_oE}P;~Y|h' );
define( 'AUTH_SALT',        '49u60<,!s#Jj48%2H,*e@b`n4p~_5;W!Xyu?Oy3- g!d16QyQt,h#_q-JFT~j6&2' );
define( 'SECURE_AUTH_SALT', 'FVUCg7x_d:.5i:62ruX)0[2i2g=P[|uj%9sZ.h2+xeY`Oq:_^hRSj,;L]WxJY$mQ' );
define( 'LOGGED_IN_SALT',   '5>(Jco/TWJ;n4TzWWX7u>hs)SvZS+r3Ol|3{)~l]u}.)%^tnqrGo.6C[ae^w4U@*' );
define( 'NONCE_SALT',       '?B:-efM*2n~Kz@ ?QE!Oqv*L/i(wP9PF)[Kw4QtP7W%h]P,jl&.(okUA2?B2/S~4' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

define( 'DISALLOW_FILE_EDIT', true );

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

define( 'WP_CACHE', true ); // Added by Hummingbird
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
