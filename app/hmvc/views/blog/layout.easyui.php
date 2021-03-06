<!DOCTYPE html>
<html lang="id">
<head>
	<title>Pusaka Blog</title>

	<meta charset="utf-8">  
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">

  	<link rel="stylesheet" type="text/css" href="@url('assets/vendor/pusaka/tailwind.min.css')">
  	<link rel="stylesheet" type="text/css" href="@url('assets/vendor/pusaka/pusaka.blog.css?v=')<< date('YmdHis') . uniqid() >>">

</head>
<body class="light-theme">

	<div class="navbar">
		<div class="container">
			<div class="brand">
				<div class="image">
					<img src="@url('assets/vendor/pusaka/logo-gold.png')">
				</div>
				<div class="text">
					<h1>
						<span>Pusaka</span>
						<span>Framework</span>
					</h1>
				</div>
			</div>
			<div class="menu">
				<div class="item">
					<a href="">Dasar I</a>
				</div>
				<div class="item">
					<a href="">Dasar II</a>
				</div>
				<div class="item">
					<a href="">Tutorial</a>
				</div>
			</div>
		</div>
	</div>

	<div class="body">
		
		<div class="box bg-primary pad-vr">
			<div class="container">
				<div class="flex flex-row items-center justify-between">
					<h1 class="font-bold text-lg">Welcome to Pusaka Blogger</h1>
					<div class="search-box">
						<input type="text" placeholder="Cari kata kunci ..." />
					</div>
				</div>
			</div>
		</div>
		
		<div class="box pad-vr">
			<div class="container">

				<div class="py-2"></div>

				<div class="grid grid-cols-3 gap-8">
					
					@for($i=0; $i<4; $i++)	
					<div class="max-w-sm rounded overflow-hidden">
						<img class="object-cover h-48 w-full" src="https://www.petanikode.com/img/cover/ci4.png" alt="Sunset in the mountains">
						<div class="px-6 py-4 bg-secondary text-white">
							<div class="text-sm mb-2">
								<a href="">Dasar I - Pemrograman Dasar PHP mengenal variabel</a>
							</div>
						</div>
					</div>
					@endfor;
					<div class="max-w-sm rounded overflow-hidden">
						<img class="object-cover h-48 w-full" src="https://repository-images.githubusercontent.com/134285701/635de980-586d-11ea-9220-1a3211239c30" alt="Sunset in the mountains">
						<div class="px-6 py-4 bg-secondary text-white">
							<div class="text-sm mb-2">
								<a href="">Dasar I - Pemrograman Dasar PHP mengenal variabel</a>
							</div>
						</div>
					</div>
					
				</div>

				<div class="py-4"></div>
				
				<div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
				  <div class="flex-1 flex justify-between sm:hidden">
				    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
				      Previous
				    </a>
				    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
				      Next
				    </a>
				  </div>
				  <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
				    <div>
				      <p class="text-sm leading-5 text-gray-700">
				        Showing
				        <span class="font-medium">1</span>
				        to
				        <span class="font-medium">10</span>
				        of
				        <span class="font-medium">97</span>
				        results
				      </p>
				    </div>
				    <div>
				      <nav class="relative z-0 inline-flex shadow-sm">
				        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="Previous">
				          <!-- Heroicon name: chevron-left -->
				          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
				            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
				          </svg>
				        </a>
				        <a href="#" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
				          1
				        </a>
				        <a href="#" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
				          2
				        </a>
				        <a href="#" class="hidden md:inline-flex -ml-px relative items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
				          3
				        </a>
				        <span class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700">
				          ...
				        </span>
				        <a href="#" class="hidden md:inline-flex -ml-px relative items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
				          8
				        </a>
				        <a href="#" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
				          9
				        </a>
				        <a href="#" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
				          10
				        </a>
				        <a href="#" class="-ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="Next">
				          <!-- Heroicon name: chevron-right -->
				          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
				            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
				          </svg>
				        </a>
				      </nav>
				    </div>
				  </div>
				</div>

			</div>
		</div>
	</div>

	<div class="footer">
		<div class="container">
			<div class="flex flex-col justify-center">
				<div class="footer-link flex flex-row py-2">
					<div class="item-link">
						<a href="@url('blog/')" 			class="text-primary">Home</a>
					</div>
					<div class="item-link">
						<a href="@url('blog/about')" 		class="text-primary">About</a>
					</div>
					<div class="item-link">
						<a href="@url('blog/contact')" 		class="text-primary">Contact</a>
					</div>
					<div class="item-link">
						<a href="@url('blog/contributor')" 	class="text-primary">Contributor</a>
					</div>
				</div>
				<div class="text-sm py-2">&copy; Copyright 2019-2020 <b class="text-primary">Pusaka Developer</b> | Made With <b class="text-primary">PusakaCMS</b></div>
			</div>
		</div>
	</div>

	<div class="py-4 bg-gray-900"></div>

	<?php view() ?>

</body>
</html>