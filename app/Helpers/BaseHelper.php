<?php

namespace App\Helpers;

class BaseHelper
{
    /**
     * Get admin prefix
     * 
     * @return string
     */
    public static function getAdminPrefix(): string
    {
        return config('core.routes.admin.prefix', 'admin');
    }

    /**
     * Lấy path prefix của resource trong config (vd. 'chi_nhanh' -> 'chi-nhanh').
     */
    public static function getRoutePrefix(string $routeKey): string
    {
        $prefix = config('core.routes.'.$routeKey.'.prefix');

        return $prefix !== null ? (string) $prefix : $routeKey;
    }

    /**
     * Get user prefix
     * 
     * @return string
     */
    public static function getUserPrefix(): string
    {
        return config('core.routes.user_frontend.prefix', '');
    }

    /**
     * Custom route name
     * 
     * @param string $prefix
     * @param string $routePrefix
     * @return array
     */
    public function customRouteName(string $prefix, string $routePrefix): array
    {
        return [
            'names' => [
                'index' => $prefix . '.' . $routePrefix . '.index',
                'create' => $prefix . '.' . $routePrefix . '.create',
                'store' => $prefix . '.' . $routePrefix . '.store',
                'show' => $prefix . '.' . $routePrefix . '.show',
                'edit' => $prefix . '.' . $routePrefix . '.edit',
                'update' => $prefix . '.' . $routePrefix . '.update',
                'destroy' => $prefix . '.' . $routePrefix . '.destroy',
            ]
        ];
    }
}

