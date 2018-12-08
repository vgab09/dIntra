<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.01.
 * Time: 19:22
 */

namespace App\Http\Middleware;


use App\Http\Components\Alert\Alert;
use Closure;
use Illuminate\Support\ViewErrorBag;

class CollectSessionErrors
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $viewBag = $request->session()->get('errors');

        if(!empty($viewBag)){
            app('AlertProvider')->mergeSessionErrors($viewBag->all(),Alert::ERROR);
        }

        return $next($request);
    }

}