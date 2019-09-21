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