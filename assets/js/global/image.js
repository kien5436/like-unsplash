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