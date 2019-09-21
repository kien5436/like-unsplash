// search header
document.querySelector('.search-box input[type=text]').addEventListener('input', function() {
	if (this.value.trim().length > 0)
		document.getElementById('btn-reset').style.setProperty('display', 'initial');
	else 
		document.getElementById('btn-reset').style.setProperty('display', 'none');
});
document.querySelector('.search-box input[type=text]').addEventListener('mouseover', function() {
	if (this.value.trim().length > 0)
		document.getElementById('btn-reset').style.setProperty('display', 'initial');
	else 
		document.getElementById('btn-reset').style.setProperty('display', 'none');
});

document.getElementById('btn-reset').addEventListener('click', function() {
	this.previousElementSibling.value = '';
	this.previousElementSibling.focus();
	this.style.setProperty('display', 'none');
});

document.getElementById('search-form').addEventListener('submit', function(e) {
	e.preventDefault();
	window.location = '/tim-kiem/' + this.querySelector('input[name=se]').value.trim();
});

// navigation - signed in
/** show form upload */	
try {
	document.getElementsByClassName('btn-upload')[0].addEventListener('click', function() {
		if (document.getElementsByClassName('upload').length == 0) {
			$.ajax({
				url: '/Photos/loadFormUpload',
				success: function(form) {
					document.querySelector('body script').insertAdjacentHTML('beforebegin', form);
					
					let script = document.createElement('script'), jsSrc = document.getElementById('uploadjs');
					script.src = jsSrc.getAttribute('data-src');
					document.body.appendChild(script);
					jsSrc.remove();
					
					setTimeout(function() {
						document.getElementsByClassName('upload')[0].css({
							'opacity': 1,
							'visibility': 'visible'
						});
					}, 10);
				},
				error: function(error) {
					console.error('Error on loadFormUpload: ', error);
				}
			});
		}
		else document.getElementsByClassName('upload')[0].css({
			'opacity': 1,
			'visibility': 'visible'
		});
	});
} catch(err) {
	console.error(err);
}

/** hidden menu */
document.addEventListener('click', function(e) {
	let clicked = e.target.closest('.btn-dropdown');
	if (clicked) {
		let menu = document.getElementsByClassName('btn-dropdown-menu'),
			 thisMenu = clicked.getElementsByClassName('btn-dropdown-menu')[0];
		for (let i = menu.length - 1; i >= 0; i--) {
			if (menu[i] === thisMenu) thisMenu.classList.toggle('show');
			else menu[i].classList.remove('show');
		}
	}
	else
		!!document.querySelector('.btn-dropdown-menu.show') && document.querySelector('.btn-dropdown-menu.show').classList.remove('show');
});