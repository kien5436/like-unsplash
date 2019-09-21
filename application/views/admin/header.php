<header class="header">
	<div class="logo">
		<a href="<?= base_url('/') ?>">
			<i class="fa fa-camera-retro logo-img"></i>
			<p>LikeUnsplash</p>
		</a>
	</div>
	<div class="burger-btn">
		<input type="checkbox" id="burger" checked>
		<label for="burger"><span class="burger"></span></label>
	</div>
	<ul class="nav-link">
		<li class="nav-link-2">
			<div class="btn btn-dropdown avatar">
				<img src="<?= $pp ?>">
				<ul class="btn-dropdown-menu">
					<li class="btn-dropdown-item">
						<a href="<?= sprintf('%s%s.%s', base_url('trang-ca-nhan/'), $_COOKIE['username'], $_COOKIE['uid']) ?>">Trang cá nhân</a>
					</li>
					<li class="btn-dropdown-item">
					   <a href="<?= sprintf('%s%s.%s', base_url('tai-khoan/'), $_COOKIE['username'], $_COOKIE['uid']) ?>">Cài đặt tài khoản</a>
					</li>
					<li class="btn-dropdown-item">
						<a href="<?= base_url('dang-xuat') ?>">Đăng xuất</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-link-2">
			<div class="btn btn-dropdown notification">
				<i class="fa fa-bell"></i>
				<i class="btn-title">Thông báo</i>
				<ul class="btn-dropdown-menu">
					<li class="btn-dropdown-item">
						<a href="javascript:void(0)">New notification</a>
					</li>
					<li class="btn-dropdown-item">
						<a href="javascript:void(0)">New notification</a>
					</li>
				</ul>
			</div>
		</li>
	</ul>
</header>