<div class="aside">
	<div class="introduction">
		<h1 class="introduction-logo"><i class="fa fa-camera-retro"></i><span>LikeUnsplash</span></h1>
		<p class="introduction-text"><?= $introText ?></p>
	</div>
</div>
<div class="form">
	<div class="form-content">
		<div class="introduction introduction-mobile">
			<h1 class="introduction-logo"><i class="fa fa-camera-retro"></i><span>LikeUnsplash</span></h1>
			<p class="introduction-text"><?= $introText ?></p>
		</div>
		<form action="<?= $action ?>" method="post">
			<div class="form-redirect">
				<?= $redirect ?>
				<a href="javascript:void(0)" class="btn btn-fb"><i class="fa fa-facebook"></i><span>Facebook</span></a>
				<a href="javascript:void(0)" class="btn btn-gg"><i class="fa fa-google-plus"></i><span>Google+</span></a>
				<p>Hoặc</p>
			</div>
			<?= $items ?>
		</form>
	</div>
</div>