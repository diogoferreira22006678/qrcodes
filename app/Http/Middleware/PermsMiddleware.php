<?php namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class PermsMiddleware {
  use \App\Traits\ApiUtils;

  public function handle($request, Closure $next, $perm_name=null){
    $user = User::getCurrent();
    if($user == null){
      return redirect('/login')->with(['redirect' => $request->url()]);
    }
    if($user->user_super || $perm_name == null || substr($perm_name, 0, 1) == '_') goto next;

    return $this->permError($request);

    next:
    \View::share('user', $user);
    return $next($request);
  }

  private function permError($request){
    $err = "Não tens permissões para fazer esta operação.";
    $route = $request->route();
    $action = $route->action;
    $middleware = $action['middleware'];
    if(in_array('api_exception', $middleware)){
      throw new \Exception($err);
    }else{
      return back()->withErrors(['popup-error' => $err]);
    }
  }

}
