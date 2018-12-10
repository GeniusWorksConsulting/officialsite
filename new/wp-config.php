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

/*
 * cPanel & WHM® Site Software
 *
 * Core updates should be disabled entirely by the cPanel & WHM® Site Software
 * plugin, as Site Software will provide the updates.  The following line acts
 * as a safeguard, to avoid automatically updating if that plugin is disabled.
 *
 * Allowing updates outside of the Site Software interface in cPanel & WHM®
 * could lead to DATA LOSS.
 *
 * Re-enable automatic background updates at your own risk.
 */
define( 'WP_AUTO_UPDATE_CORE', false );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'geniuswo_sendy');

/** MySQL database username */
define('DB_USER', 'geniuswo_wp');

/** MySQL database password */
define('DB_PASSWORD', 'WY/d3Nj>');

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
define('AUTH_KEY',         'Sok8HtU1c7UV_zHoAr2bBK9G6hv26apAkzA1_XH02kPK_TSRPMbf5l50WHKKB237');
define('SECURE_AUTH_KEY',  'nzb0fOCu9c7pqlI9bkgpPYfxOytbYgFd9vwoYW7MCQ1dFIG6xbLxESABlLdgHi9m');
define('LOGGED_IN_KEY',    'aRecqGLlvrmfPlkX97hERMVnZlXRCPhJe4RmkMmxnvy5lDJ2bxK_uPvki5OKJ1w5');
define('NONCE_KEY',        'pWZ5jtSOB5T84njLLtjvWNEnNPbkwYUASZNpcZHWMrfCsfoM7ispHvu553JyXxoj');
define('AUTH_SALT',        'Z_GQTHPzfx49cErvQoirGIwbgM0n6JBixYm6Ysta0COJOyrEfq3ixVRoKG4J5gd3');
define('SECURE_AUTH_SALT', 'tH3XGefYIOMiFpaxSo32Djh1oytRWiW0qiEA0Va0ePs_AfoYdzZbVSDaXVNP57j7');
define('LOGGED_IN_SALT',   '4qRbngI2eC69_pgy6ovDW3rqdF8e6gAlu2JE8ylCHvXqmF8bwn37Kte7RkYkT3OR');
define('NONCE_SALT',       'DIeG7_aWmAHkpoYKjqU7c2f2CbuU57NtoLLmD9ODNItcGOyMZRDfMD8uaNoIcR1l');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'new_';

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
