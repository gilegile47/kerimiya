<?php
/**
 * Keremiya Framework
 * Geliştiren Kerem Demirbaş
 * http://www.keremiya.com
 * http://twitter.com/Keremiya
 * ben@keremiya.com
 */
$dir = get_template_directory() . '/panel';

// Önemli Dosyalar
include_once ($dir . '/functions.php');
include_once ($dir . '/default-options.php');
include_once ($dir . '/update-options.php');

include_once ($dir . '/post-options.php');
include_once ($dir . '/panel-options.php');
include_once ($dir . '/notifier.php');
include_once ($dir . '/ui.php');

include_once ($dir . '/importer/imdb.php');

include_once ($dir . '/update-theme.php');

?>