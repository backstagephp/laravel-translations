<?php

namespace Backstage\Translations\Laravel\Http\Middleware;

use Backstage\Translations\Laravel\Models\Language;
use Illuminate\Support\Facades\Response;

class SwitchRouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, \Closure $next)
    {
        if (! $request->user()) {
            return $next($request);
        }

        $query = $request->query();

        if (! $query) {
            return $next($request);
        }

        if (!isset($query['switchLocaleTo']) || empty($query['switchLocaleTo'])) {
            return $next($request);
        }

        $locale = $query['switchLocaleTo'];

        $existingLocale = Language::query()->where('code', $locale)->exists();

        if (! $existingLocale) {
            return $next($request);
        }

        $locale = Language::query()->where('code', $locale)->first();

        if ($locale) {
            $request->user()->setLocale($locale);
        }

        /**
         * Remove the switchLocaleTo query string from the URL
         */
        $urlWithoutGivenInput = $request->fullUrlWithoutQuery([
            'switchLocaleTo',
        ]);

        return Response::redirectTo($urlWithoutGivenInput, 302);
    }
}
