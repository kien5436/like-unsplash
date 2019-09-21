<div class="search">
   <p class="search-text">Bộ lọc:</p>
   <form class="search-form" action="<?= base_url('quan-tri/quan-li-nguoi-dung/tim-kiem') ?>" id="filter">
      <div class="search-opt">
         <select class="select" name="field" id="field">
            <option value="" selected disabled>Chọn một mục</option>
            <?php $opt = [
               'slug_name' => 'Tên',
               'email' => 'Email',
               'sex' => 'Giới tính',
               'location' => 'Địa điểm',
               'role' => 'Vai trò',
            ];
            foreach ($opt as $k => $v) {
               echo sprintf('<option value="%s"%s>%s</option>', 
                  $k, $selected = (isset($_GET['field']) && $_GET['field'] == $k) ? ' selected' : null, $v);
            }
            ?>
         </select>
         <?= form_error('field') ?>
      </div>
      <div class="search-opt" id="value">
         <?= form_error('value') ?>
      </div>
      <div class="search-opt">
         <select id="order" class="select" name="order">
            <?php $opt = [
               'desc' => 'Giảm dần',
               'asc' => 'Tăng dần'
            ];
            foreach ($opt as $k => $v) {
               echo sprintf('<option value="%s"%s>%s</option>', 
                  $k, $selected = (isset($_GET['order']) && $_GET['order'] == $k) ? ' selected' : null, $v);
            }
            ?>
         </select>
         <?= form_error('order') ?>
      </div>
      <div class="search-opt">
         <input class="btn" type="submit" value="Lọc"/>
      </div>
   </form>
</div>
<div class="users">
	<?php
   if (is_array($users)) {
      foreach ($users as $user) { ?>
      <div class="user">
         <img class="user-pp" src="<?= $user['pp'] ?>"/>
         <div class="user-info">
            <a class="user-info-name" href="<?= base_url(sprintf('trang-ca-nhan/%s.%s', $user['slug_name'], $user['uid'])) ?>">
               <?php echo $user['username']; if ($user['role'] === 'admin') echo ' <i class="fa fa-shield"></i>' ?>
            </a>
            <p class="user-info-overview">
               <span><i class="fa fa-picture-o"></i><b><?= $user['photos'] ?></b></span>
               <span><i class="fa fa-heart"></i><b><?= $user['loved'] ?></b></span>
            </p>
         </div>
         <i class="fa fa-times rm-user" title="xóa tài khoản" id="<?= $user['uid'] ?>"></i>
      </div>
   <?php }
   }
   else echo $users; ?>
</div>
<template id="value-sex">
   <option selected disabled>Chọn giới tính</option>
   <?php foreach ($opts[1] as $k => $v) {
      echo sprintf('<option value="%u"%s>%s</option>',
         $k, $selected = (isset($_GET['value']) && $_GET['value'] === (string) $k) ? ' selected' : null, $v);
   } ?>
</template>
<template id="value-role">
   <option selected disabled>Chọn vai trò</option>
   <?php foreach ($opts[0] as $k => $v) {
      echo sprintf('<option value="%u"%s>%s</option>',
         $k, $selected = (isset($_GET['value']) && $_GET['value'] === $k) ? ' selected' : null, $v);
   } ?>
</template>
<template id="value-other">
   <input name="value" placeholder="Gõ từ khóa" value="<?php if ( isset($_GET['value']) ) echo $_GET['value'] ?>" type="text">
</template>