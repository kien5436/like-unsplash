<aside class="sidebar">
	<ul class="menu">
		<?php foreach ($menu as $item) {
		echo sprintf('
			<li class="menu-item">
				<a href="%s" class="%s"><i class="%s"></i><span>%s</span></a>
			</li>', $item['url'], $item['active'], $item['icon'], $item['text']);		
		} ?>
	</ul>
</aside>