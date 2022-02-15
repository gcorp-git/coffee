<? return [
	'root' => [
		'server' => [
			'charset' => 'utf-8',
			'time_limit' => 30,
			'memory_limit' => '128M',
			'session_start' => false,
		],
		'path' => [
			'tools' => HOME . '/src/tools',
			'logs' => HOME . '/logs',
		],
		'apps' => [
			'api' => HOME . '/root/apps/api/api.php',
			'web' => HOME . '/root/apps/web/web.php',
		],
		'routes' => [
			'api' => 'api',
			'' => 'web',
		],
	],
	'api' => [
		'methods' => [
			'auth/sign-in' => [
				'type' => 'post',
				'class' => 'APIMethod_auth_signIn',
				'file' => HOME . '/src/api/auth/sign-in.php',
			],
			'auth/sign-out' => [
				'type' => 'post',
				'class' => 'APIMethod_auth_signOut',
				'file' => HOME . '/src/api/auth/sign-out.php',
			],
		],
	],
	'web' => [
		'path' => [
			'pages' => HOME . '/src/pages',
			'chunks' => HOME . '/src/chunks',
			'snippets' => HOME . '/src/snippets',
		],
	],
];