<?php
namespace App\Helpers;

class ResponseHelper {
  public static function getResponse($status, $message, $data) {
    $response = array (
      'status' => $status,
      'message' => $message,
      'data' => $data
    );

    return $response;
  }
}