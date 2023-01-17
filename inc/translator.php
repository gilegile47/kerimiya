<?php

$WPLANG = get_option("WPLANG");

$DIR = get_template_directory()."/lang/$WPLANG.php";
$DIR_EN = get_template_directory()."/lang/en_EN.php";

// GET LANGUAGE
if(file_exists($DIR)) { 
	$k_lang = include $DIR;
} else {
	$k_lang = include $DIR_EN;
}

function _k($name) {
	global $k_lang;
	$lang = apply_filters('keremiya_lang', $k_lang);

	if($lang[$name]) {
		echo $lang[$name];
	} else {
		echo $name; 
	}
}

function _k_($name) {
	global $k_lang;
	$lang = apply_filters('keremiya_lang', $k_lang);

	if($lang[$name]) {
		return $lang[$name];
	} else {
		return $name; 
	}
}


$DIR = get_template_directory()."/lang/panel/$WPLANG.php";
$DIR_EN = get_template_directory()."/lang/panel/en_EN.php";

// GET LANGUAGE
if(file_exists($DIR)) { 
	$kp_lang = include $DIR;
} else {
	$kp_lang = include $DIR_EN;
}

function _kp($name) {
	global $kp_lang;

	if($kp_lang[$name]) {
		echo $kp_lang[$name];
	} else {
		echo $name; 
	}
}

function _kp_($name) {
	global $kp_lang;

	if($kp_lang[$name]) {
		return $kp_lang[$name];
	} else {
		return $name; 
	}
}
?>