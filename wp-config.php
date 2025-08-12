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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
// define( 'DB_NAME', 'db_ntbksense' );
define( 'DB_NAME', 'ntbksense' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

// if ( !defined('WP_CLI') ) {
//     define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
//     define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
// }

if ( !defined('WP_CLI') ) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    define( 'WP_SITEURL', $scheme . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $scheme . '://' . $_SERVER['HTTP_HOST'] );
}




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
define( 'AUTH_KEY',         '9tZzQlVhodAXSJatrZh3MknWFHzO8t2T0muBAQ3rYr35Qpx3Y1YuScQ4fBuiQUsS' );
define( 'SECURE_AUTH_KEY',  'sZiXMVhgG0n2XKuJ8P1wFLSXUZoPMFYFeC2TkXI3Rq5ihwQisD7Z27qXPG9yDTWG' );
define( 'LOGGED_IN_KEY',    'Ug2ozn3ktIbqsChwbheEsKTZtrlEKad0KgRKZKZfFpww4cBcGu4bDQJWyFrHeDCT' );
define( 'NONCE_KEY',        'zhruxfKl9ebIPQPT3fj0yMD9KzSAXlgZOvvgjujvTIqAZRPZykFeCMlK2rA1Korp' );
define( 'AUTH_SALT',        '7vqyFf5fi8aGLbVJvCS1LhReGwgJSbZ0rlReGw30b1VDBVz9GYrA8Setli5rvo5w' );
define( 'SECURE_AUTH_SALT', 'n1Py6EG1BKTLfMcEyGCfpRHICDsb0qF6ley3BUR7iAVycVeFirwJYRyG13XYPkTn' );
define( 'LOGGED_IN_SALT',   'sqjEBKh2br79O38S3iCBOhkgRc17HtChk9YJPzaAe9suDnyNhtcDZtE7uvtc7FEh' );
define( 'NONCE_SALT',       'PkSmHGsWe3RSih0JAyBhxzgwBZzfjmAZ3pe4CFdacVGzPIM1H9INekYHbRzxywTO' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
// define( 'WP_DEBUG', false );

// Ubah dari 'false' menjadi 'true'
define( 'WP_DEBUG', true );

// Tambahkan baris ini di bawahnya untuk menyimpan log error
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false ); // Biar error nggak tampil di layar
/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
