<?
if (empty($_SESSION['auth'])) {
	web::error(401, web::page('auth'));
}
