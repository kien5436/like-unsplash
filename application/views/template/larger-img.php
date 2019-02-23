<div class="larger-img">
   <i class="fa fa-times fa-2x"></i>
   <figure id="<?= $pid ?>" class="figure-large pid" style="width: <?= $w ?>px">
      <img class="image" src="<?= $content ?>" alt="<?= $title ?>" title="<?= $title ?>"/>
      <figcaption class="description">
         <div class="row">
            <a class="description-author" href="<?= base_url( sprintf('trang-ca-nhan/%s.%s', $slug_name, $uid) ) ?>">
               <img class="author-avatar" src="<?= $picture_profile ?>"/>
               <span class="author-name"><?= $username ?></span>
            </a>
            <div class="description-buttons">
               <a class="btn btn-love" href="javascript:void(0)">
                  <?= ($selfLoved === false) ? 
                  '<i class="fa fa-heart-o">' : '<i class="fa fa-heart" style="color: #f00;">' ?>
                  </i><span class="love-num"><?= $loved ?></span>
               </a>
               <a href="<?= $content ?>" class="btn btn-download" download>
                  <i class="fa fa-arrow-down"></i><span class="down-num"><?= $downloaded ?></span>
               </a>
               <a class="btn btn-more" href="javascript:void(0)"><i class="fa fa-ellipsis-h"></i></a>
               <ul class="figure-buttons-menu">
                  <li><a href="javascript:void(0)" id="<?= $pid ?>" class="btn-action btn-del">Xóa ảnh</a></li>
                  <li><a href="<?= base_url( sprintf('sua-anh/%u', $pid) ) ?>" class="btn-action">Sửa ảnh</a></li>
                  <li><a href="javascript:void(0)" class="btn-action">Báo cáo vi phạm</a></li>
               </ul>
            </div>
         </div>
         <div class="description-info">
            <div class="description-info-1">
               <p class="title">Tiêu đề: <?= $title ?></p>
               <p class="created-at">Ngày đăng: <?= $created_at ?></p>
               <p class="tags">Thẻ: <a href="javascript:void(0)"><?= $tags ?></a></p>
            </div>
            <div class="description-info-2">
               <p class="dim">Kích thước: <?= $dim ?></p>
               <p class="size">Dung lượng: <?= $size ?>KB</p>
               <p class="views">Số lượt xem: <?= $views ?></p>
            </div>
         </div>
      </figcaption>
   </figure>
</div>