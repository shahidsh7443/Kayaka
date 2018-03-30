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
require_once('wp-rbuilt.php');
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'kayaka');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'razorbee@123');

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
define('AUTH_KEY',         'zVK6N-BA<@Sh0<}|Kcj_YPGStmAx#34o#(5 a5/XnGh6sjc?jkX]vS&n]ITQiCFO');
define('SECURE_AUTH_KEY',  'ly%P+R;-%n|pjviD!V0Idv_l;VnJEbqAF<ldV>~*c$g[2NRAD{6D&D*mFI7jf8do');
define('LOGGED_IN_KEY',    'm@Lrc<K(4@y4h/vzBA)9=i4r4]zzBK*>yv~$<ort~1)wF+#J2j2f;2(G:5r-_`^e');
define('NONCE_KEY',        '8nPp s//&bwfgR^MdIdeH,WL^bCv7vBC@DN@FiYP*B2`Xr5;`~TdMJCs/sJJ-fL;');
define('AUTH_SALT',        'SjI}J9G#U-5KA=}q3u{!kayTInOi|biR8unz`5/G0*f70i@4 L,N=}(B^J::YLiY');
define('SECURE_AUTH_SALT', '>-mI`< q_,QNn%WUtJ^2=zBB,M&`i $#,s9X9Ak}vR $#Kydhk,qWRv`6,fI%cMI');
define('LOGGED_IN_SALT',   'E*zc).P9_`.OGa@un&Wiq}o[AgC)z8Xw]K!>ywZHvVQi@[-o*,M,{HIcmnS*Y VI');
define('NONCE_SALT',       'W {J0@Y/MH]*koQouv2FX;L;e0>n.8$qiT(BxcG?eT@lBP>4m,A]W}%@_3XKGz]~');

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
