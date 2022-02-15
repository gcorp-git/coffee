<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Auth</title>
</head>
<body>
	<form id="auth" action="/api/auth/sign-in" method="post">
		<input type="text" name="login">
		<input type="password" name="password">
		<input type="submit" value="Auth">
	</form>
</body>
</html>
