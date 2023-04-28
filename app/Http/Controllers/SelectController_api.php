<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SelectController_api extends Controller{
  use \App\Traits\ApiUtils;

  private function search($query, $name, $request){
    $page = $request->page - 1 ?? 0;

    $query = $query->where($name, 'LIKE', '%'.$request->q.'%');

    $count = (clone $query)->count();
    $query = $query->skip($page * self::PAGING)
    ->take(self::PAGING)
    ->get();
    return $this->apiResponseSelect($query, $count, self::PAGING);
  }

  const PAGING = 10;

  public function categories(Request $request){
    $query = Category::select('category_id as id', 'category_name as text');
    return $this->search($query, 'category_name', $request);
  }
}
