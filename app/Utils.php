<?php
namespace App;

class Utils{

    // Use to access objects with dot notation
    public static function dot_notation($obj, $dot){
      $data = $obj;
      $keys = explode(".", $dot);
      foreach($keys as $key){
        if(gettype($data) == "object"){
          if(!isset($data->$key)) return null;
          $data = $data->$key;
        }else if(gettype($data) == "array"){
          if(!isset($data[$key])) return null;
          $data = $data[$key];
        }else{
          return null;
        }
      }
      return $data;
    }

    public static function toCamelCase($str){
      $str = str_replace('_', '', ucwords($str, '_'));
      $str = strtolower($str[0]) . substr($str, 1);
      return $str;
    }
}
