<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'windpexplorer');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', '');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'stTV<)%*$Z8w<3I]m(o=L-x[>[RcL 5$.Q+91$}u(6hPf>+{^n|Srb$A#V1Klqt;'); // Cambia esto por tu frase aleatoria.
define('SECURE_AUTH_KEY', '@WMy7G3y*Y$ogqEH`Ty;^G+>;DXRHkt3R_C.-z1qEv1Gvav_`AIFvch/LS&+WA0N'); // Cambia esto por tu frase aleatoria.
define('LOGGED_IN_KEY', '):GXr}_ii@oaM._~NEV3$$]SiG-[Jkf:g[|)D*PBopyIh+GbfP8o-1-Zc|O`9*6U'); // Cambia esto por tu frase aleatoria.
define('NONCE_KEY', 'pR[q+aplzrA@iNrWYiJ`FJ7`|Ws<0IG!7R<w+7qm0T[3I#[_[o -`+@;OV]M1^[b'); // Cambia esto por tu frase aleatoria.
define('AUTH_SALT', ';j/u?GTr|+<QNtlu{E-A?GB[u#$d&-?%@?K ;v6&^HJL[(.x&!>/<B>)V,GM+FYG'); // Cambia esto por tu frase aleatoria.
define('SECURE_AUTH_SALT', '}NblpA)+|SI-Yw-6pg<&|(RHOH(^=E_<-[NI8y{D-1Sw>~_$Y#1c>xU858=/=)Qk'); // Cambia esto por tu frase aleatoria.
define('LOGGED_IN_SALT', '@(Amk3>{cN$jf%|G|ExdhUZsh6O?fFhS+:IYk`2SXyj7h f zt9+016`%R(0.Zc5'); // Cambia esto por tu frase aleatoria.
define('NONCE_SALT', 'q^!ZDZ^]&E#w!lwg09^<AZ8ZQjx$h2TO~>p=lThrgIYl:.U1AM/.b]jGMyvRw6,n'); // Cambia esto por tu frase aleatoria.

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = '82uskpz_';

/**
 * Idioma de WordPress.
 *
 * Cambia lo siguiente para tener WordPress en tu idioma. El correspondiente archivo MO
 * del lenguaje elegido debe encontrarse en wp-content/languages.
 * Por ejemplo, instala ca_ES.mo copiándolo a wp-content/languages y define WPLANG como 'ca_ES'
 * para traducir WordPress al catalán.
 */
define('WPLANG', 'es_ES');

/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

