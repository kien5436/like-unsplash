Element.prototype.css = function (style) {
   for (prop in style) {
      if (style.hasOwnProperty(prop))
         this.style.setProperty(prop, style[prop]);
   }
}
Element.prototype.hasClass = function (className) {
   return this.classList.contains(className) || this.parentNode.classList.contains(className);
}

function isEmail(email) {
	const testMail = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
	return testMail.test(email.trim());
}

function inArray(arr, find) {
	return arr.indexOf(find) != -1;
}

function validate(type, ...values) {
	switch (type) {
		case 'email': return isEmail(values[0]);
		case 'in array': return inArray(values[0], values[1]);
		default: return 'what do you want?';
	}
}

function showError(obj, msg) {
	if ( (obj.nextElementSibling &&	obj.nextElementSibling.id !== `${obj.type}-error`) || !obj.nextElementSibling ) {
		const p = document.createElement("p");
		p.id = `${obj.type}-error`;
		p.classList.add('error-input');
		p.innerHTML = msg;
		obj.parentNode.appendChild(p);
	}

	obj.focus();
	return false;
}

document.addEventListener('input', function(e) {
	if (e.target.tagName == 'INPUT') {
		let next = e.target.nextElementSibling;
		if ( next && next.className == 'error-input' ) next.remove();
	}
});

document.addEventListener('change', function(e) {
	if (e.target.tagName == 'INPUT') {
		let next = e.target.parentNode.getElementsByClassName('error-input')[0];
		if ( next && next.className == 'error-input' ) next.remove();
	}
});

let lastScrollTop = pageYOffset || document.documentElement.scrollTop;

window.addEventListener("scroll", function() {
	let st = pageYOffset || document.documentElement.scrollTop;
	/** 
	- compare last croll top with current one, if this greater means user is scrolling down
	- get window's offset and height to detect if user almost scrolls to bottom
	*/
	if ( st > lastScrollTop && pageYOffset + innerHeight + 300 >= document.body.clientHeight ) {
		let se, next;
		// search for photos
		if (window.location.pathname.indexOf('trang-ca-nhan') >= 0) {
			se = Array.pop( window.location.pathname.split('.') )
			se = JSON.stringify({uid: se});
		}
		else se = document.getElementById('hidden-se').value.trim();

		try {
			next = document.getElementById('next-p').value;
			document.getElementById('next-p').remove();

			$.ajax({
				url: '/Photos/loadMore',
				data: {
					offset: next,
					kw: se
				},
				success: function(photos) {
					document.getElementsByClassName('masonry')[0].insertAdjacentHTML('beforeend', photos);
				},
				error: function(err) { console.error('Error on loading more photos: ', err); }
			});
		} catch (err) {
			console.clear();
		}
	}
	lastScrollTop = st <= 0 ? 0 : st;
}, false);

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

document.addEventListener('click', function(e) {
	const target = e.target,
			modal = document.getElementsByClassName('modal')[0];

	if (target.hasClass('thumbnail')) {
		let pid = target.closest('.figure').id,
		smLove = target.closest('.figure').querySelector('.btn-love'),
		smDown = target.closest('.figure').querySelector('.btn-download');
		document.body.style.setProperty('overflow', 'hidden');

		$.ajax({
			url: '/Photos/viewLarge/' + pid,
			success: function(largeImg) {
				document.querySelector('body script').insertAdjacentHTML('beforebegin', largeImg);

				document.getElementsByClassName('larger-img')[0].addEventListener('click', function(e) {
					switch (e.target.className) {
						case 'fa fa-times fa-2x':
						this.remove();
						document.body.style.setProperty('overflow', 'auto');
						break;
						case 'btn btn-love':
						case 'fa fa-heart-o':
						case 'fa fa-heart':
						case 'love-num':
						love(smLove, false);
						break;
						case 'btn btn-download':
						case 'fa fa-arrow-down':
						case 'down-num':
						updateDownload(smDown, false);
						break;
						case 'btn btn-more':
						case 'fa fa-ellipsis-h':
						break;
					}
				});
			},
			error: function(err) {console.error('Error on loading larger image: ', err); }
		});
	}
	else if (target.hasClass('btn-love')) {
		love(target);
	}
	else if (target.hasClass('btn-download')) {
		updateDownload(target);
	}
	else if (target.hasClass('btn-more')) {
		target.closest('.btn-more').nextElementSibling.classList.toggle('show');
	}
	else if (target.hasClass('btn-del')) {
		modal.classList.remove('hide');
		modal.classList.add('show');
		document.getElementById('modal-cancel').focus();
		document.getElementById('modal-accept').previousElementSibling.value = target.id;
	}
	else if (target.id == 'modal-cancel') {
		modal.classList.remove('show');
		modal.classList.add('hide');
	}
	else if (target.id == 'modal-accept') {
		delPhoto(target, target.previousElementSibling.value);
	}
	else if (target.hasClass('btn-update')) {
		updatePhoto(target.id);
	}
});

let thumbnail = document.getElementsByClassName('thumbnail');
for (let i = thumbnail.length - 1; i >= 0; i--) {
	thumbnail[i].addEventListener('mouseout', function(e) {
		// e.target.className === 'thumbnail' &&
		this.closest('.figure').getElementsByClassName('figure-buttons-menu')[0].classList.remove('show');
	});
}

function love(heart, update = true) {
	heart = heart.closest('.btn-love').getElementsByClassName('fa')[0];
	let loveNum = heart.nextElementSibling;

	if ( heart.classList.contains('fa-heart-o') ) {// love
		heart.classList.remove('fa-heart-o');
		heart.classList.add('fa-heart');
		heart.style.setProperty('color', '#f00');

		loveNum.innerHTML = parseInt(loveNum.innerHTML) + 1;
	}
	else {// unlove
		heart.classList.remove('fa-heart');
		heart.classList.add('fa-heart-o');
		heart.style.setProperty('color', 'unset');

		loveNum.innerHTML = parseInt(loveNum.innerHTML) - 1;
	}

	if (update) {
		$.ajax({
			url: '/Photos/seeAndLove/love/' + heart.closest('.pid').id,
			success: function(res) { window.location = res; },
			error: function(err) { console.error('Error on loving a photo: ', err); }
		});
	}
}

/** update number of downloaded photo */
function updateDownload(downloaded, update = true) {
	let num = downloaded.closest('.btn-download').getElementsByTagName('span')[0];
	num.innerHTML = parseInt(num.innerHTML) + 1;

	if (update) {
		$.ajax({
			url: '/Photos/seeAndLove/download/' + downloaded.closest('.pid').id,
			error: function(err) { console.error('Error on updating downloaded photo: ', err); }
		});	
	}
}

function delPhoto(btnDel, pid) {
	$.ajax({
		url: '/Photos/delPhoto/' + pid,
		success: function(res) {
			res = JSON.parse(res);
			document.body.insertAdjacentHTML('beforeend', res.notif);
			res.success === true && btnDel.closest('.figure').remove();
		},
		error: function(err) { console.error('Error on deleting photo: ', err); }
	});
}

function updatePhoto(id) {
	$.ajax({
		url: '/sua-anh/' + id,
		success: function(res) {
			document.body.insertAdjacentHTML('beforeend', res);
			document.getElementsByClassName('upload-image')[0].remove(); // remove input file

			let script = document.createElement('script'), jsSrc = document.getElementById('uploadjs');
			script.src = jsSrc.getAttribute('data-src');
			document.body.appendChild(script);
			jsSrc.remove();

			document.getElementsByClassName('preview')[0].setAttribute('data', '');
			
			document.getElementsByClassName('upload')[0].css({
				'visibility': 'visible',
				'opacity': 1
			});
		}
	});
}