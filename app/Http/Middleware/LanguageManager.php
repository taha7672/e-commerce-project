<?php

namespace App\Http\Middleware;

use App\Models\SitesSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;


class LanguageManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
          // Check if the session has a locale value
          if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            $selectedLanguage = SitesSetting::with('language')->first('selected_language_id');
            if ($selectedLanguage && $selectedLanguage->language) {
                App::setLocale($selectedLanguage->language->language_code == 'tr' ? 'tr' : 'en');
            }
            else{
                App::setLocale('en');
            }
        }
        return $next($request);
    }
}
