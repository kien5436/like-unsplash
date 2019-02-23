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

// window.addEventListener('resize', toggleTabNav('.profile'));
// window.addEventListener('load', toggleTabNav('.profile'));

/**
 * change tab of .tab css snippet when click
 * @method changeTab
 * @param  {object}  elem    clicked tab
 * @param  {array}  	navTab navigate tabs list
 */
 function changeTab(elem, navTab) {
 	let href = elem.href.replace(/.*#(\w+)/i, '$1'), href2;
 	for (let i = 0; i < navTab.length; i++) {
 		if (navTab[i] === elem) {
 			elem.classList.add('tab-nav-active');
 			document.getElementById(href).classList.add('tab-pane-active');
 		}
 		else if ( navTab[i].classList.contains('tab-nav-active') ) {
 			navTab[i].classList.remove('tab-nav-active');
 			href2 = navTab[i].href.replace(/.*#(\w+)/i, '$1');
 			document.getElementById(href2).classList.remove('tab-pane-active');
 		}
 	}
 }

/**
 * toggle navigator when minimize screen or use phoen
 * @method toggleNavTab
 * @param  {dom}     parent tab navigation's parent
 */
 function toggleNavTab(parent) {
 	if (document.body.clientWidth <= 720) {
 		document.querySelector(`${parent} .tab-item:first-child`).addEventListener('click', function() {
 			document.querySelectorAll(`${parent} .tab-item:not(:first-child)`).foreach(function(el) {
 				el.style.display = (el.style.display == 'none') ? 'block' : 'none';
 			});
 		});
 	}
 }

// origin
let setting =  document.querySelectorAll('.settings .tab-nav');

for (let i = setting.length - 1; i >= 0; i--) {
	setting[i].addEventListener('click', function(e) {
		e.preventDefault();
		changeTab(this, setting);
	});
}

// show a pseudo image for previewing
let pp = document.querySelector('#settings input[type="file"]'), checkImg = true;

if (window.File && window.FileReader && window.FileList && window.Blob) {
	pp.addEventListener('change', function(e) {
		let img = e.target.files[0];

		if(img && img.type.match('image.*')) {
			let fr = new FileReader(), image = new Image();
			fr.onload = function(evt) {
				let ext = img.name.slice((img.name.lastIndexOf(".") - 1 >>> 0) + 2); // get file extension

				if ( !inArray(['JPG', 'JPEG', 'PNG', 'GIF'], ext.toUpperCase()) ) {
					pp.value = '';
					checkImg = false;
					return showError(pp, 'Ảnh phải thuộc định dạng JPG, JPEG, PNG hoặc GIF');
				}
				else checkImg = true;

				if (!!document.querySelector('.user-pp.preview-image'))
					document.getElementsByClassName('user-pp')[1].src =  evt.target.result;
				else {
					document.getElementsByClassName('user-pp')[1].classList.add('preview-image');
					document.getElementsByClassName('user-pp')[1].src =  evt.target.result;
				}
			};
			fr.readAsDataURL(img);
			// get dimensions of the image
			image.onload = function() {
				if (this.width > 260) {
					checkImg = showError(pp, 'Chiều rộng tối đa là 260px');
					pp.value = '';
				}
			}
			image.src = (window.URL || window.webkitURL).createObjectURL(img);
		}
		else {
			checkImg = showError(pp, 'Không đúng định dạng ảnh');
			pp.value = '';
		}
	}, false);
} else {
	console.warn('The browser does not support File API');
}

let sex = document.querySelectorAll('input[name=sex]');
for (let i = sex.length - 1; i >= 0; i--) {
	sex[i].addEventListener('click', function(e) {
		document.querySelector('.pseudo-radio.checked').classList.remove('checked');
		this.nextElementSibling.firstElementChild.classList.add('checked');
	});
}

// validate settings form
document.querySelector('#settings form').addEventListener('submit', function(e) {
	e.preventDefault();

	let email = document.querySelector('input[name="email"]'),
	lname = document.querySelector('input[name="lname"]'),
	fname = document.querySelector('input[name="fname"]'),
	interests = document.querySelector('input[name="interests"]'),
	location = document.querySelector('input[name="location"]');

	if (email.value.trim() === '')
		return showError(email, 'Không được để trống Email');
	else if (isEmail(email.value) === false)
		return showError(email, 'Email không hợp lệ');
	else if (lname.value.trim() === '')
		return showError(lname, 'Không được để trống Họ');
	else if (lname.value.trim().length > 40)
		return showError(lname, 'Họ quá dài');
	else if (fname.value.trim() === '')
		return showError(fname, 'Không được để trống Tên');
	else if (fname.value.trim().length > 10)
		return showError(fname, 'Tên quá dài');
	else if (interests.value.trim().length > 100)
		return showError(interests, 'Có vẻ như bạn có quá nhiều Sở thích');
	else if (location.value.trim().length > 255)
		return showError(location, 'Địa điểm quá dài');
	else if (!checkImg) return false;

	this.submit();
});
// validate password form
document.querySelector('#change-password form').addEventListener('submit', function(e) {
	e.preventDefault();

	let newPwd = document.querySelector('input[name="new"]'),
	old = document.querySelector('input[name="old"]'),
	verify = document.querySelector('input[name="verify"]');

	if (old.value.trim() === '')
		return showError(old, 'Không được để trống Mật khẩu cũ');
	else if (old.value.trim().length < 6)
		return showError(old, 'Mật khẩu quá ngắn');
	else if (newPwd.value.trim() === '')
		return showError(newPwd, 'Không được để trống Mật khẩu mới');
	else if (newPwd.value.trim().length < 6)
		return showError(newPwd, 'Mật khẩu quá ngắn');
	else if (verify.value.trim() === '')
		return showError(verify, 'Không được để trống Xác nhận mật khẩu');
	else if (verify.value.trim() !== newPwd.value.trim())
		return showError(verify, 'Xác nhận mật khẩu không khớp với mật khẩu mới');

	this.submit();
});