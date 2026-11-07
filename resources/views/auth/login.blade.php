<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
</head>
<body>
	<main>
		<h1>Login</h1>

		@if (session('status'))
			<p>{{ session('status') }}</p>
		@endif

		@if ($errors->any())
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		@endif

		<form method="POST" action="{{ route('login') }}">
			@csrf

			<div>
				<label for="email">Email</label>
				<input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
			</div>

			<div>
				<label for="password">Password</label>
				<input id="password" type="password" name="password" required>
			</div>

			<label>
				<input type="checkbox" name="remember"> Remember me
			</label>

			<button type="submit">Login</button>
		</form>
	</main>
</body>
</html>
