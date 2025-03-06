<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Repositories\SettingRepository;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    /**
     * use Illuminate\Support\Facades\Session;
     */
    public function handle(Request $request, Closure $next): Response
    {

        $localeLanguage = \Session::get('languageName');
         $settings = App::make(SettingRepository::class)->getSyncList();
        if (!isset($localeLanguage)) {
            if(!empty($settings['default_lang'])){
                \App::setLocale($settings['default_lang']);
            }else{
                \App::setLocale('en');
            }
        } else {
            \App::setLocale($localeLanguage);
        }

        return $next($request);
    }
}
