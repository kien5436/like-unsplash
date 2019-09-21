<div class="upload">
   <i class="fa fa-times fa-2x close"></i>
   <form action="<?= base_url('Photos/submitPhoto') ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?php if (isset($photo['pid'])) echo $photo['pid'] ?>">
      <div class="preview" data="Ấn vào đây hoặc kéo thả ảnh để tải lên">
         <?php if (isset($photo['content'])) {
            echo sprintf('<img src="%s" class="preview-image">', $photo['content']);
         } ?>
         <input class="upload-image" type="file" name="image" accept="image/*">
      </div>
      <div class="description">
         <div class="description-col">
            <span>Tiêu đề ảnh</span>
            <input type="text" name="title" placeholder="Tiêu đề ảnh" value="<?php if (isset($photo['title'])) echo $photo['title'] ?>">
         </div>
         <div class="description-col">
            <span>Gắn thẻ</span>
            <input type="text" name="tags" placeholder="Gắn thẻ (tối đa 5 thẻ)" value="<?php if (isset($photo['tags'])) echo $photo['tags'] ?>">
         </div>
         <input class="btn" type="submit" value="<?= (isset($photo['pid'])) ? 'Cập nhật' : 'Tải ảnh lên' ?>"/>
      </div>
   </form>
</div>
<div id="uploadjs" data-src="<?= '/vendor/js/user/upload.min.js' ?>"></div>