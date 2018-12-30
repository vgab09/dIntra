<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.01.
 * Time: 19:22
 */

namespace App\Http\Middleware;


use App\Http\Components\Alert\Alert;
use App\Http\Components\Providers\AlertProvider;
use Closure;
use Illuminate\Support\ViewErrorBag;

class CollectSessionMessages
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

        /**
         * @var AlertProvider $alertProvider
         */
        $alertProvider = app('AlertProvider');
        $alertProvider->merge(Alert::ERROR, $request->session()->get('errors'));
        $alertProvider->merge(Alert::ERROR, $request->session()->get(Alert::ERROR));
        $alertProvider->merge(Alert::ERROR, $request->session()->get(Alert::WARNING));
        $alertProvider->merge(Alert::SUCCESS, $request->session()->get(Alert::SUCCESS));
        $alertProvider->merge(Alert::INFO, $request->session()->get(Alert::INFO));


        return $next($request);
    }

}