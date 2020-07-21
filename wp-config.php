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
define( 'DB_NAME', 'dc_moficios' );

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
define( 'AUTH_KEY',         'XD<-232CJJD>F1=0Tv389Mu))CFd$}1QvRkxPtq@6f2y A!>q;!J+`{M&F:b%?)6' );
define( 'SECURE_AUTH_KEY',  'LIjgG&xF%f=!&VOdLO^Ew~%dcfU$HFJPHB!w&FE,8KS)&qVD#P6IDe!1(Zcq5 @(' );
define( 'LOGGED_IN_KEY',    'FyW_ <2Hy[/%.pnrwTpF3kXc-U8b[>v,vOp^,yM:wLhSUhNso$L}e^F*O)x5T-hN' );
define( 'NONCE_KEY',        '$>N}*=gR 3sP6R^IU3a0Eln/$dsAsE}b<VSJ4;fAZz^AGe,4Y&%Cy#yUj/h(;LVC' );
define( 'AUTH_SALT',        'K<Z2[%ZT!|BU89jm,e:lwS ~eaRrG7ilnE^cZCcL!h,>ZjJlZf%R-9Q5}[)[`vu.' );
define( 'SECURE_AUTH_SALT', '5qUR#o5&<;x/<$y~ss3!C:Cf{[zmXMBCP4.iSIoK~y}a61U;[E2@q,h6Y=o-96lP' );
define( 'LOGGED_IN_SALT',   'OPpKV*njmtvHcdHB^FKL3N]AyD&w.bokT:@Ep2$lL@M/[ucE1NG-bHIur7M/#I5Z' );
define( 'NONCE_SALT',       'Wfoe: mz?@u,7peNw5laX_L&bG|Ep}m62T/6xUI0xc3YRwW&(C?#ob`EIh)^Nw )' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
