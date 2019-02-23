<header class="header">
   <div class="header-top">
      <div class="logo">
         <a href="<?= base_url('/') ?>">
            <i class="fa fa-camera-retro logo-img"></i>
            <p>LikeUnsplash</p>
         </a>
      </div>
      <div class="search-box">
         <form action="<?= base_url('tim-kiem') ?>" id="search-form">
            <input type="hidden" value="<?php if (isset($se)) echo $se ?>" id="hidden-se">
            <button type="submit" class="btn"><i class="fa fa-search"></i></button>
            <input type="text" name="se" placeholder="Tìm kiếm theo hình ảnh, chủ đề" value="<?php if (isset($se)) echo $se ?>" required>
            <button type="button" class="btn" id="btn-reset"><i class="fa fa-times"></i></button>
         </form>
      </div>
      <ul class="nav-link">
         <?php if (!$isValidCookie): ?>
         <li class="nav-link-2"><a href="<?= base_url('dang-ki') ?>" class="btn">Đăng kí</a></li>
         <li class="nav-link-2"><a href="<?= base_url('dang-nhap') ?>" class="btn">Đăng nhập</a></li>
         <?php else: ?>
         <li class="nav-link-3">
            <div class="btn btn-dropdown avatar">
               <img src="<?= $pp ?>">
               <ul class="btn-dropdown-menu">
                  <li class="btn-dropdown-item">
                     <a href="<?= sprintf('%s%s.%s', base_url('trang-ca-nhan/'), $_COOKIE['username'], $_COOKIE['uid']) ?>">Trang cá nhân</a>
                  </li>
                  <li class="btn-dropdown-item">
                     <a href="<?= sprintf('%s%s.%s', base_url('tai-khoan/'), $_COOKIE['username'], $_COOKIE['uid']) ?>">Cài đặt tài khoản</a>
                  </li>
                  <?php if ($granted): ?>
                  <li class="btn-dropdown-item">
                     <a href="/quan-tri">Quản lí trang</a>
                  </li>
                  <?php endif ?>
                  <li class="btn-dropdown-item">
                     <a href="<?= base_url('dang-xuat') ?>">Đăng xuất</a>
                  </li>
               </ul>
            </div>
         </li>
         <li class="nav-link-3">
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
         <li class="nav-link-3">
            <a href="javascript:void(0)" class="btn btn-upload">
               <i class="fa fa-arrow-up"></i>
               <i class="btn-title">Tải ảnh lên</i>
            </a>
         </li>   
         <?php endif ?>        
      </ul>
   </div>
   <div class="header-bottom">
      <ul class="tags">
         <li class="tag-prev"><i class="fa fa-angle-left"></i></li>
         <?php if (isset($tags)) {
            for ($i = count($tags) - 1; $i >= 0; $i--) {
               echo sprintf('<li><a href="%s%2$s" class="btn">%2$s</a></li>', base_url('tim-kiem/'), $tags[$i]['tag_name']);
            }
         } ?>
         <li class="tag-next"><i class="fa fa-angle-right"></i></li>
      </ul>
   </div>      
</header>