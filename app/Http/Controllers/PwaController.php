<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PwaController extends Controller
{
    public function manifest()
    {
        return $this->manifestContent();
    }

    private function manifestContent()
    {
        return view('manifest', [
            'subIcon' => $this->isSubdomain() ? 'z/' : ''
        ]);
    }

    private function isSubdomain()
    {
        return explode('.', URL::to('/')) > 2;
    }
}
