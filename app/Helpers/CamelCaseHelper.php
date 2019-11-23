<?php 
namespace App\Helpers;
use Illuminate\Support\Str;

class CamelCaseHelper {
  public static function intoCamelArray($array) {
    $data = [];

    foreach ($array as $key => $value) {
      $data[] = self::convertTocamel($value);
    }

    return $data;
  }

  public static function convertTocamel($values) {
		$result = new \stdClass;
		foreach ($values as $key => $value) {
			$newKey = Str::camel($key);
			$result->$newKey = $value;
		}

		return $result;
  }
  
}