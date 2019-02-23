<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Slug {
	private const CHAR_MAP = array(
		'-' => '', '&' => '', '"' => '', "'" => '',
		// '!' => '', '?' => '', '%' => '', '$' => '', '#' => '', '@' => '', '\\' => '', '/' => '', '+' => '',
		'đ' => 'd',
		'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ạ' => 'a', 'ã' => 'a',
		'ă' => 'a', 'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ặ' => 'a', 'ẵ' => 'a',
		'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ậ' => 'a', 'ẫ' => 'a',
		'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
		'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ệ' => 'e', 'ễ' => 'e',
		'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
		'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
		'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o', 
		'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o', 'ô' => 'o', 
		'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o', 'ơ' => 'o',
		'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
		'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u', 'ư' => 'u',
	);

	private function transliterate($str)
	{
		$str = mb_strtolower($str, 'UTF-8');
		$str = str_replace(array_keys(self::CHAR_MAP), self::CHAR_MAP, $str);
		return preg_replace('/\s{2,}/', '', $str);
	}

	public function slugify($str)
	{
		$str = $this->transliterate($str);
		return preg_replace( '/[^\w.+{}()!?%$#@\/\\\]/', '-', $str);
	}
}