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