(function() {

	var toggle 	= document.querySelector('.menu-toggle');
	var sidebar = document.querySelector('.sidebar');
	var overlay = document.querySelector('.overlay');

	var show  = function(e) {
		sidebar.classList.add("show");
		overlay.classList.add("show");
	}

	var hide  = function(e) {
		sidebar.classList.remove("show");
		overlay.classList.remove("show");
	}

	toggle.addEventListener('click', show);
	overlay.addEventListener('click', hide);

})();