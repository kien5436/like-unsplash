<div class="profile">
	<div class="profile-wrapper">
	<?php
		echo sprintf('
			<img src="%s" class="profile-picture">
			<div class="profile-misc">
				<h2>%s</h2>',
			$user['picture_profile'], $user['username']);

		echo sprintf('
			<p class="profile-misc-1"><i class="fa fa-heart"></i><span>%s</span></p>
			<p class="profile-misc-1"><i class="fa fa-map-marker"></i><span>%s</span></p>', $user['interests'], $user['location']);

		if ($isValidCookie && $_COOKIE['uid'] == $user['uid']) {
			echo sprintf('<a href="%s" class="btn">Cài đặt tài khoản</a>',
				sprintf('%s%s.%s', base_url('tai-khoan/'), $_COOKIE['username'], $_COOKIE['uid']));
		}
	echo '</div>';
	?>
	</div>
</div>
<div class="gallery">
	<ul class="tab tab-horizontal">
		<li class="tab-item"><a href="#photo" class="tab-nav tab-nav-active">Ảnh</a></li>
		<li class="tab-item"><a href="#collection" class="tab-nav">Bộ sưu tập</a></li>
		<li class="tab-item"><a href="#loved" class="tab-nav">Ảnh đã thích</a></li>
		<li class="tab-item"><a href="#following" class="tab-nav">Đang theo dõi</a></li>
		<li class="tab-item"><a href="#follower" class="tab-nav">Người theo dõi</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane tab-pane-active" id="photo">
		<?= (isset($masonry)) ? $masonry : '<p class="empty">Chưa có ảnh nào</p>'; ?>
		</div>
		<div class="tab-pane" id="collection"></div>
		<div class="tab-pane" id="loved"></div>
		<div class="tab-pane" id="following"></div>
		<div class="tab-pane" id="follower"></div>
	</div>
</div>
<?php if ( isset($masonry) ) echo $modal ?>