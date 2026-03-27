<?php

namespace Modules\PublicSite\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ErrorPageController extends Controller
{
    public function show(int $code): Response
    {
        $allowed = [401, 403, 404, 419, 429, 500, 503];
        abort_unless(in_array($code, $allowed, true), 404);

        return response()->view("errors.$code", ['code' => $code], $code);
    }
}
