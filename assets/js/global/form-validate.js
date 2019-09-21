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