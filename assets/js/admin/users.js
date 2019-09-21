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

// burger button
document.getElementById('burger').addEventListener('click', function() {
	let sidebar = document.getElementsByClassName('sidebar')[0],
	container = document.getElementsByClassName('container')[0];

	if (sidebar.getBoundingClientRect().left === 0) {
		sidebar.style.setProperty('left', '-100%');
		container.style.setProperty('margin-left', 0);
	} else {
		sidebar.style.setProperty('left', 0);
		container.style.setProperty('margin-left', '20%');
	}
});

// activate menu
let a = document.querySelectorAll('.menu-item a');
for (let i = a.length - 1; i >= 0; i--) {
	if (location.pathname.indexOf('/quan-tri') >= 0) { a[0].className = 'active'; break; }
	if (location.href == a[i].href) a[i].className = 'active';
	else a[i].className = '';
}

// origin
document.addEventListener('click', function(e) {
	const target = e.target, modal = document.getElementsByClassName('modal')[0];

	if (target.hasClass('rm-user')) {
		modal.classList.remove('hide');
		modal.classList.add('show');
		document.getElementById('modal-cancel').focus();
		document.getElementById('modal-accept').previousElementSibling.value = target.id;
		rmUser = target;
	}
	else if (target.id == 'modal-cancel') {
		modal.classList.remove('show');
		modal.classList.add('hide');
	}
	else if (target.id == 'modal-accept') {
		
		let uid = document.getElementById('modal-accept').previousElementSibling.value
		
		$.ajax({
			url: '/Users/delUser/' + uid,
			success: function(res) {
				document.getElementById('modal-cancel').click();
				res = JSON.parse(res);
				document.body.insertAdjacentHTML('beforeend', res.notif);
				!res.error && document.getElementById(uid).closest('.user').remove();
			},
			error: function(err) {
				console.error('Error on deleting user');
			}
		});
	}
});

// filter
document.getElementById('field').addEventListener('change', displayValue);
window.addEventListener('load', displayValue);

// display value field for filter option
function displayValue() {
	let val, chose = document.getElementById('field').value;
	switch (chose) {
		case 'slug_name':
		case 'email':
		case 'location':
		val = document.getElementById('value-other').content.cloneNode(true);
		break;
		case 'sex':
		case 'role':
		let opts = document.getElementById(`value-${chose}`).content.cloneNode(true);
		if (!!opts) {
			val = document.createElement('select');
			val.name = 'value';
			val.className = 'select';
			val.appendChild(opts);
		}
		break;
		default:
		break;
	}

	if (val !== undefined) {
		document.getElementById('value').firstElementChild &&
		document.getElementById('value').firstElementChild.className != 'error-input' &&
		document.getElementById('value').firstElementChild.remove();
		document.getElementById('value').appendChild(val);
	}
}

// validate filter
document.getElementById('filter').addEventListener('submit', function(e) {
	e.preventDefault();

	let field = document.getElementById('field'),
	val = document.querySelector('#value input') || document.querySelector('#value select'),
	order = document.getElementById('order');
	const allowedField = ['slug_name', 'email', 'sex', 'location', 'role'],
	allowedOrder = ['desc', 'asc'];

	if (val !== null && val.value.trim() === '')
		return showError(val, 'Chưa có điều kiện lọc');
	else if (field.value.trim() === '')
		return showError(field, 'Chưa chọn điều kiện lọc');
	else if ( validate('in array', allowedField, field.value.trim()) == false )
		return showError(field, 'Bộ lọc không hợp lệ');
	else if (order.value.trim() === '')
		return showError(order, 'Chưa chọn sắp xếp kết quả lọc');
	else if ( validate('in array', allowedOrder, order.value.trim()) == false )
		return showError(order, 'Bộ lọc không hợp lệ');

	this.submit();
});