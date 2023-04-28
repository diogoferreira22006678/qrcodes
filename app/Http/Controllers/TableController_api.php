<?php
namespace App\Http\Controllers;

use App\Models\Doc;
use App\Models\Folder;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Utils;

class TableController_api extends Controller{
  use \App\Traits\ApiUtils;

  /* Utils */
  private function searchQuery($query, $request){
    $q = $request['search']['value'];
    if($q == null) return;


    $parts = preg_split("/\\s+/", $q);
    foreach($parts as $q){
      $q = strtolower("%$q%");

      $query->where(function($query) use ($request, $q){
        $where = 'where';
        foreach($request['columns'] as $col){
          if($col['searchable'] === 'false') continue;
          if($col['name'] === null) continue;

          $parts = explode('.', $col['name']);
          if(count($parts) > 1){
            $useWhere = true;
            $recurse = function($query) use (&$parts, $request, $q, $where, &$useWhere, &$recurse, &$www){
              $first = array_shift($parts);

              if(reset($parts) !== false){
                $w = $useWhere?$where.'Has':'whereHas';
                $useWhere = false;
                $www[] = $w;
                $first = Utils::toCamelCase($first);
                $query->{$w}($first, function($query) use (&$recurse){
                  $recurse($query);
                });
              }else{
                $query->where($first, 'LIKE', $q);
              }
            };
            $recurse($query);
          }else{
            $query->{$where}($parts[0], 'LIKE', $q);
          }

          $where = 'orWhere';
        }
      });
    }
  }
  private function searchOrder($query, $request){
    foreach($request['order'] as $o){
      $col = $request['columns'][$o['column']];
      $parts = explode('.', $col['name']);
      if(count($parts) > 1){
        // TODO make it work with deeper relations (currently 1 deep)
        $model = $query->getModel();
        $p0 = $parts[0];
        $p0 = Utils::toCamelCase($p0);
        $p1 = $parts[1];
        $q = $model::whereHas($p0, function($query) use ($p1){$query->select($p1);});

        $matches = [];
        preg_match('/\((.+)\)/', $q->toSql(), $matches);
        $q = '('.$matches[1].' limit 1)';
        $query->orderBy(\DB::raw($q), $o['dir']);
      }else{
        $query->orderBy($col['name'], $o['dir']);
      }
    }
  }
  private function searchLimit($query, $request){
    $start = $request['start'];
    $length = $request['length'];
    $query->skip($start)->take($length);
  }
  private function search($query, $request){
    $count = $query->count();
    $query->addSelect('*');
    $this->searchQuery($query, $request);
    $countFiltered = (clone $query)->count();
    $this->searchOrder($query, $request);
    $this->searchLimit($query, $request);

    // dd($query->toSql(), $request->input());

    return $this->rawApiResponse([
      'draw' => $request['draw'],
      'recordsTotal' => $count,
      'recordsFiltered' => $countFiltered,
      'data' => $query->get()
    ]);
  }

  /* Calls */
  public function docs(Request $request, $folder_id){
    $query = Doc::whereHas('folder', function($query) use ($folder_id){
      $query->where('folder_id', $folder_id);
    });
    return $this->search($query, $request);
  }

  public function folders(Request $request){
    $query = Folder::with('categories');
    return $this->search($query, $request);
  }

  public function users(Request $request){
    $query = User::query();
    return $this->search($query, $request);
  }
  
  public function categories(Request $request){
    $query = Category::query();
    return $this->search($query, $request);
  }

}
