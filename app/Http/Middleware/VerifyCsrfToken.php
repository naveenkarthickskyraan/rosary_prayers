<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        'sub_category_list', 'app_list_api','category_list_by_app_id','question_list_by_category_id','question_list_by_sub_category_id', 'question_detail'
    ];
}
