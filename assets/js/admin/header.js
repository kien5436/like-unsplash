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