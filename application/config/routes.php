<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
// user ----------------------------------------------------------
$route['tim-kiem/(:any)'] = 'Home/search/$1';
$route['tim-kiem'] = 'Home/search';
$route['sua-anh/(:num)'] = 'Photos/loadFormUpdate/$1';
$route['trang-ca-nhan/(:any)\.(:num)'] = 'Profile/loadViewProfile/$1/$2';
$route['tai-khoan/(:any)\.(:num)'] = 'Profile/loadViewSettings/$1/$2';
$route['dang-xuat'] = 'Signin/signout';
$route['dang-nhap'] = 'Signin';
$route['dang-ki'] = 'Signup';
$route['sua-thong-tin'] = 'Profile/updateProfile';
$route['doi-mat-khau'] = 'Profile/changePassword';
// admin --------------------------------------------------------
$route['quan-tri/quan-li-nguoi-dung'] = 'Users';
$route['quan-tri/quan-li-nguoi-dung/tim-kiem'] = 'Users/search';
$route['quan-tri/quan-li-anh'] = 'Home';
$route['quan-tri/quan-li-the'] = 'Tags';
$route['quan-tri/thong-bao'] = 'Notification';
$route['quan-tri/thong-ke'] = 'Statistic';
$route['quan-tri'] = 'Statistic';