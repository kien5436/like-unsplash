<?php
$tabItems = [
   'info' => 'Thông tin',
   'settings' => 'Cài đặt',
   'change-password' => 'Đổi mật khẩu'
];
$arr = [
   'Email' => $email,
   'Sở thích' => $interests,
   'Địa điểm' => $location,
   'Giới tính' => $sex
];
$html = '';

// generate user-info 1 class contents
foreach ($arr as $title => $text) {
   $html = sprintf('%s
      <p class="user-info">
        <span class="user-info-title">%s</span>
        <span class="user-info-text">%s</span>
      </p>', $html, $title, $text);
}

// generate info tab contents
$html = sprintf('
   <div class="user user-1">
      <img src="%s" class="user-pp">
      <h3 class="user-fullname">%s</h3>
   </div>
   <div class="user user-2">%s</div>',
   $picture_profile,
   sprintf('%s %s', $lname, $fname),
   $html);

// generate user-1 contents in settings
$html2 = sprintf('
   <div class="user user-1">
      <img src="%s" class="user-pp preview-image">
      <input type="file" name="pp" id="pp" accept="image/*">
      %s
      <label for="pp" class="btn">Chọn ảnh mới</label>
   </div>',
   $picture_profile,
   form_error('pp', '<p id="file-error" class="error-input">', '</p>') );

// generate user-2 contents in settings
$arr = [
   ['Email', $email, 'email'],
   ['Họ', $lname, 'lname'],
   ['Tên', $fname, 'fname'],
   ['Sở thích', $interests, 'interests'],
   ['Địa điểm', $location, 'location'],
];
$html3 = '';

for ($i = 0; $i < count($arr); ++$i) {
   $html3 = sprintf('%s
      <div class="user-info error">
         <span class="user-info-title">%s</span>
         <input type="text" class="user-info-text" value="%s" name="%s">
         %s
      </div>',
      $html3, $arr[$i][0], $arr[$i][1], $arr[$i][2],
      form_error($arr[$i][2], '<p id="text-error" class="error-input">', '</p>'));
}

// sex field - used in $html3
$html4 = '';
for ($i = 0; $i < count($asex); $i++) {
   $html4 = sprintf('%1$s
      <li>
         <input type="radio" name="sex" id="sex-%2$d" value="%2$s" %4$s>
         <label for="sex-%2$d"><span class="pseudo-radio%4$s"></span>%3$s</label>
      </li>',
      $html4, $i, $asex[$i], ($sex == $asex[$i]) ? ' checked' : null);
}

$html4 = sprintf('
   <div class="user-info error">
      <span class="user-info-title">Giới tính</span>
      <ul class="user-info-text">%s</ul>
   </div>', $html4);

$html2 = sprintf('
   <form action="%s" method="post" enctype="multipart/form-data">
      %s
      <div class="user user-2">%s %s</div>
      <input type="submit" class="btn" value="Cập nhật">
   </form>', base_url('sua-thong-tin'), $html2, $html3, $html4);

// generate change password form
$html3 = sprintf('
   <form action="%s" method="post">
      <div class="user-info error">
         <span class="user-info-title">Mật khẩu cũ</span>
         <input type="password" class="user-info-text" name="old" autofocus>
         %s
      </div>
      <div class="user-info error">
         <span class="user-info-title">Mật khẩu mới</span>
         <input type="password" class="user-info-text" name="new">
         %s
      </div>
      <div class="user-info error">
         <span class="user-info-title">Xác nhận mật khẩu mới</span>
         <input type="password" class="user-info-text" name="verify">
         %s
      </div>
      <input type="submit" class="btn" value="Cập nhật">
   </form>',
   base_url('doi-mat-khau'),
   form_error('old', '<p id="password-error" class="error-input">', '</p>'),
   form_error('new', '<p id="password-error" class="error-input">', '</p>'),
   form_error('verify', '<p id="password-error" class="error-input">', '</p>') );

$tabContent = [
   'info' => $html,
   'settings' => $html2,
   'change-password' => $html3
];
?>
<div class="settings">
   <ul class="tab">
      <!-- <li class="tab-item">&#9776;</li> -->
      <?php
      foreach ($tabItems as $href => $text) {
         $activeTab = ($doing == $href) ? ' tab-nav-active' : '';
         echo sprintf('<li class="tab-item"><a class="tab-nav%s" href="#%s">%s</a></li>', $activeTab, $href, $text);
      }
      ?>
   </ul>
   <div class="tab-content">
      <?php foreach ($tabContent as $id => $contents) {
         $activeTab = ($doing == $id) ? ' tab-pane-active' : '';
         echo sprintf('<div class="tab-pane%s" id="%s">%s</div>', $activeTab, $id, $contents);
      } ?>      
   </div>
</div>