// hide uploading form
document.getElementsByClassName('close')[0].addEventListener('click', function() {
	this.parentNode.css({
		'opacity': 0,
		'visibility': 'hidden'
	});
});
// show a pseudo image for previewing
try {
	let file = document.getElementsByClassName('upload-image')[0], checkImg = true;

	if (window.File && window.FileReader && window.FileList && window.Blob) {
		file.addEventListener('change', readImg, false);
	} else {
		console.warn('The browser does not support File API');
	}

	function readImg(e) {
		document.getElementsByClassName('preview')[0].setAttribute('data', '');

		let img = e.target.files[0];

		if (img && img.type.match('image.*')) {
			let fr = new FileReader(), image = new Image();
			fr.onload = function(evt) {
				if (!!document.getElementById('preview-image')) {
					document.getElementById('preview-image').src =  evt.target.result;
				} else {
					const previewImg = document.createElement('img');
					previewImg.id = 'preview-image';
					previewImg.classList.add('preview-image');
					previewImg.src = evt.target.result;
					file.parentNode.insertBefore(previewImg, file);
				}
			};
			fr.readAsDataURL(img);
			// get dimensions of the image
			image.onload = function() {
				if (this.width < 400 || this.height < 100) {
					showError(file, 'Kích thước ảnh tối thiểu là 400x100');
					file.value = '';
				}
			}
			image.src = (window.URL || window.webkitURL).createObjectURL(img);
		}
		else {
			showError(file, 'Không đúng định dạng ảnh');
			file.value = '';
		}
	}
} catch (err) {
	console.warn(err);
}

// validate form
document.querySelector('.upload form').addEventListener('submit', function(e) {
	e.preventDefault();

	let title = document.querySelector('input[name=title]'),
		 tags = document.querySelector('input[name=tags]'),
		 img = document.querySelector('input[name=image]');

	if (title.value.trim() === '') {
		title.parentNode.classList.add('error');
		return showError(title, 'Phải có tiêu đề của ảnh');
	}
	else if (title.value.trim().length > 255) {
		title.parentNode.classList.add('error');
		return showError(title, 'Tiêu đề quá dài');
	}
	else if (tags.value.trim() === '') {
		tags.parentNode.classList.add('error');
		return showError(tags, 'Phải gắn thẻ cho ảnh');
	}
	else if (tags.value.trim().split(',').length > 5) {
		tags.parentNode.classList.add('error');
		return showError(tags, 'Tối đa 5 thẻ');
	}
	else if (!!img && img.value.trim() === '')
		return showError(img, 'Chưa có ảnh nào được chọn');

	this.submit();
});

window.addEventListener('beforeunload', function(e) {
	if (isUploading()) {
		e.preventDefault();
		(e || window.event).returnValue = 'a';
		return 'a';
	}
});

function isUploading() {
	let fields = document.querySelectorAll('.description-col input');

	for (let i = fields.length - 1; i >= 0; i--)
		if (fields[i].value.trim() !== '') return true;
	return document.getElementsByClassName('upload-image')[0].value !== '';
}