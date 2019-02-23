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