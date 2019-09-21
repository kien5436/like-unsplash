function isEmail(email) {
	const testMail = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
	return testMail.test(email.trim());
}

function validate(type, value) {
	switch (type) {
		case 'email': return isEmail(value);
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

// origin
document.getElementsByTagName('form')[0].addEventListener('submit', function(e) {
	e.preventDefault();

	let email = document.querySelector('input[name=email]'),
	pwd = document.querySelector('input[name=password]');

	if (email.value.trim() === '')
		return showError(email, 'Không được để trống email');
	else if (isEmail(email.value) === false)
		return showError(email, 'Email không hợp lệ');
	else if (pwd.value.trim() === '')
		return showError(pwd, 'Không được để trống mật khẩu');
	else if (pwd.value.trim().length < 6)
		return showError(pwd, 'Mật khẩu quá ngắn');

	this.submit();
});

document.getElementsByTagName('form')[0].addEventListener('input', function(e) {
	let next = e.target.nextElementSibling;
	if ( next && next.id === `${e.target.type}-error` ) next.remove();
});