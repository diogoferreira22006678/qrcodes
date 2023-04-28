<?php
namespace App\Api;

use GuzzleHttp\RequestOptions;
use Http;
class BaseApi{
    protected $base_url = '';
    protected $auth = '';

    protected function get($url, $data = []){
        return $this->call('GET', $url, $data);
    }
    protected function post($url, $data = []){ 
        return $this->call('POST', $url, $data);
    }
    protected function put($url, $data = []){
        return $this->call('PUT', $url, $data);
    }
    protected function delete($url, $data = []){
        return $this->call('DELETE', $url, $data);
    }

    private function call($method, $url, $data = []){

        if($method=='GET'){
            $params = [
                RequestOptions::QUERY => $data
            ];
        }else{
            $params = [
                RequestOptions::JSON => $data
            ];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->auth)
        ])->send($method, $this->base_url.$url, $params);

        $json = json_decode($response->getBody()->getContents(), false);

        return $json;
    }


}