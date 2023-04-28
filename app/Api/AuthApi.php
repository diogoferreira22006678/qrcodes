<?php
namespace App\Api;

class AuthApi extends BaseApi{
  protected $base_url;
  protected $auth;

  public function __construct(){
    $this->base_url = env('ULHT_AUTH_URL', '');
    $this->auth = env('ULHT_AUTH_AUTH', '');
  }

  public function auth($user_id, $user_credential){
    return $this->post('/', [
      'user_id' => $user_id,
      'user_credential' => base64_encode($user_credential)
    ]);
  }

}