<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function encode($path)
    {
        return mb_convert_encoding(str_replace('/', '\\', $path), 'gb2312', "utf-8");
    }

    protected function decode($path)
    {
        return mb_convert_encoding($path, "utf-8", 'gb2312');
    }
}
