(function(){

	var setTokens 			= function(key, value, _component) {

		var token = _component.getAttribute('eui-token');
		token = JSON.parse(atob(token));
		token[key] = value;
		token = btoa(JSON.stringify(token));
		_component.setAttribute('eui-token', token);

	}

	// render event
	var renderEvent			= function() {

		try {

			var euiCompo 	= document.querySelectorAll('[eui-component]');

			// components
			euiCompo.forEach(function( component ) {

				// event:click
				//-----------------------------------------------------
				component.querySelectorAll('[x-click]').forEach(function( evt ) {

					evt.addEventListener('click', function(e){
						
						getResponseXmlHttp(
							evt.getAttribute('x-click'),
							component.getAttribute('eui-url'),
							component
						);

					});

				});

				// event:change
				//-----------------------------------------------------
				component.querySelectorAll('[x-change]').forEach(function( evt ) {

					evt.addEventListener('change', function(e){

						var el = e.target;
						
						setTokens(el.getAttribute('x-model'), el.value, component);

						getResponseXmlHttp(
							evt.getAttribute('x-change'),
							component.getAttribute('eui-url'),
							component
						);

					});

				});

			});

		}catch(e){
			console.log(e);
		}

	};

	var getResponseXmlHttp = function(_action, _url, _component) {

		var xmlhttp = new XMLHttpRequest();
		var _vars	= _component.getAttribute('eui-token');

		xmlhttp.onreadystatechange = function() {
			
			try {

				if (this.readyState == 4 && this.status == 200) {
					
					var response = JSON.parse(this.responseText);

					_component.setAttribute('eui-token', response.token);

					_component.innerHTML = response.render;

					renderEvent();

				}

			}catch(e) {
				console.log(e);
			}

		};

		xmlhttp.open("GET", _url + "?state=update&action=" + _action + '&token=' + _vars, true);
		xmlhttp.send();

	};

	renderEvent(); // init event

})();