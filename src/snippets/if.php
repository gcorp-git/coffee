<?
if (!empty($value)) {
	if (!empty($then)) {
		if (is_array($then)) {
			return web::chunk($then[0], $then[1]);
		} else {
			return web::chunk($then);
		}
	}
} else {
	if (!empty($else)) {
		if (is_array($else)) {
			return web::chunk($else[0], $else[1]);
		} else {
			return web::chunk($else);
		}
	}
}

return '';
