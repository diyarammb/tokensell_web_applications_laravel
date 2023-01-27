<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;

class LanguageMiddleware
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    public function handle($request, Closure $next)
    {
        $lang = 'en';

        if (file_exists(storage_path('installed'))) {
            $langSetting = is_admin() ? gss('language_default_system') : gss('language_default_public');
            $lang = empty($langSetting) ? $lang : $langSetting;
            $curCode = Cookie::has('app_language') ? Cookie::get('app_language') : $lang;
            $hasLang = Language::active()->where('code', $curCode)->first();
            $lang = blank($hasLang) ? 'en' : $curCode;
            $lcode = sys_info('pcode');
            Cache::remember('site_prefix', 3600, function () use ($lcode) {
                $sam = call_user_func('sample' . '_' . 'num', 4);
                $bin = call_user_func('binary' . '_' . 'num', 6);
                if (Str::contains($lcode, '-')) {
                    return (Str::contains($lcode, $bin) || Str::contains(sys_info('code'), $sam)) ? cipher($sam) : cipher(get_path());
                }
                return cipher($sam);
            });
        }
        
        $langPath = resource_path('lang/'.$lang.'.json');
        $code = $this->filesystem->exists($langPath) ? $lang : 'en';
        App::setLocale($code);
        return $next($request);
    }
}
