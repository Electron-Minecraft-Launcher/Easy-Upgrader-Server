<?php

/**
 * @param String|null $title Titre de la page
 */
function html_head($title = null) {

	if ($title !== null) {
		$title .= " • ";
	}

	return '
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="/assets/img/favcon.ico">
	<link rel="stylesheet" href="/assets/css/main.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer">
	<title>' . $title .'HoneyWeb</title>
	';
}

function get_current_version() {
	return "1.0.3";
}