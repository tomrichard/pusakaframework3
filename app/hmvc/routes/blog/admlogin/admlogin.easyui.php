<!DOCTYPE html>
<html lang="id">
<head>

	<link rel="shortcut icon" href="@url('assets/vendor/pusaka/favicon.ico')"/>

	<title>Pusaka Blogger</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="@url('assets/vendor/pusaka/tailwind.min.css')">
	<link rel="stylesheet" type="text/css" href="@url('assets/vendor/pusaka/pusaka.blog.css?v=')<< date('YmdHis') . uniqid() >>">

</head>
<body class="light-theme">
	
	<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
		<div class="max-w-md w-full">
			<div>
				<img class="mx-auto h-12 w-auto" src="@url('assets/vendor/pusaka/logo-gold.png')" alt="Workflow">
				<h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
				Sign in to your account
				</h2>
			</div>
			<form class="mt-8" action="<< url()->method('auth')->params('satu') >>" method="POST">
				<input type="hidden" name="remember" value="true">
				<div class="rounded-md shadow-sm">
					<div>
						<input aria-label="Email address" 	name="username" type="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Email address">
					</div>
					<div class="-mt-px">
						<input aria-label="Password" 		name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Password">
					</div>
				</div>
				<div class="mt-6 flex items-center justify-between">
					<div class="flex items-center">
						<input id="remember_me" type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
						<label for="remember_me" class="ml-2 block text-sm leading-5 text-gray-900">
							Remember me
						</label>
					</div>
					<div class="text-sm leading-5">
						<a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
							Forgot your password?
						</a>
					</div>
				</div>
				<div class="mt-6">
					<button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-black bg-primary hover:bg-black focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
					<span class="absolute left-0 inset-y-0 flex items-center pl-3">
						<svg class="h-5 w-5 text-black group-hover:text-black transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
						</svg>
					</span>
					Sign in
					</button>
				</div>
			</form>
		</div>
	</div>

</body>
</html>