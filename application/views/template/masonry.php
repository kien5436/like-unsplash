<div class="masonry">
   <?php if ( !empty($photos) ):
      $granted = array_pop($photos); $isValidCookie = array_pop($photos); $next = array_pop($photos);
         foreach ($photos as $photo): ?>
   <figure class="figure pid" id="<?= $photo['pid'] ?>">
      <div class="figure-buttons">
         <a href="javascript:void(0)" class="btn btn-love">
            <?= ($photo['selfLoved'] === false) ?
            '<i class="fa fa-heart-o">' : '<i class="fa fa-heart" style="color: #f00;">' ?>
            </i><span><?= $photo['loved'] ?></span>
         </a>
         <a href="<?= $photo['content'] ?>" class="btn btn-download" download>
            <i class="fa fa-arrow-down"></i><span><?= $photo['downloaded'] ?></span>
         </a>
         <a href="javascript:void(0)" class="btn btn-more">
            <i class="fa fa-ellipsis-h"></i>
         </a>
         <ul class="figure-buttons-menu">
            <?php if ( $granted || ($isValidCookie && $_COOKIE['uid'] === $photo['uid']) ): ?>
            <li><a href="javascript:void(0)" id="<?= $photo['pid'] ?>" class="btn-action btn-del">Xóa ảnh</a></li>
            <li><a href="javascript:void(0)" id="<?= $photo['pid'] ?>" class="btn-action btn-update">Sửa ảnh</a></li>
            <?php endif ?>
            <li><a href="javascript:void(0)" class="btn-action">Báo cáo vi phạm</a></li>
         </ul>
      </div>
      <picture>
         <source media="(max-width: 719px)" srcset="<?= $photo['thumbnail'][0] ?>">
         <source media="(max-width: 1023px)" srcset="<?= $photo['thumbnail'][1] ?>">
         <img src="<?= $photo['thumbnail'][2] ?>" alt="<?= $photo['title'] ?>" title="<?= $photo['title'] ?>" class="thumbnail">
      </picture>
      <figcaption class="figure-cap">
         <a href="<?= base_url(sprintf('trang-ca-nhan/%s.%s', $photo['slug_name'], $photo['uid'])) ?>" class="author">
            <img src="<?= $photo['picture_profile_50'] ?>" class="author-avatar">
            <span class="author-name"><?= $photo['username'] ?></span>
         </a>
      </figcaption>
   </figure>
   <?php endforeach; ?>
   <input type="hidden" id="next-p" value="<?= $next ?>">
   <?php elseif ( isset($se) ): echo sprintf('<div class="empty">Không có kết quả nào cho <i>%s</i></div>', $se);
         endif ?>
</div>