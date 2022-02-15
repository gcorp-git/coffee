<?
if (!is_array($value)) return '';
if (empty($as)) return '';

$result = '';

foreach ($value as $args) {
	if (!is_array($args)) {
		$args = ['value' => $args];
	}
	
	$result .= web::chunk($as, $args);
}

return $result;
