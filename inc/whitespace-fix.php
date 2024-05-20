<?php
// Exit if accessed directly.
if (!defined('ABSPATH')){
    exit;
}

function wp_whitespace_fix($input) {
	//option content
	$allowed = false;

	//option found header content
	$found = false;

	//type text
	foreach (headers_list() as $header) {
		if (preg_match("/^content-type:\\s+(text\\/|application\\/((xhtml|atom|rss)\\+xml|xml))/i", $header)) {
			$allowed = true;
		}

		if (preg_match("/^content-type:\\s+/i", $header)) {
			$found = true;
		}
	}

	//chick it !
	if ($allowed || !$found) {
		return preg_replace("/\\A\\s*/m", "", $input);
	} else {
		return $input;
	}
}

//callback
ob_start('wp_whitespace_fix');
?>
