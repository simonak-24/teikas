<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class LanguageController extends Controller
{
    /**
     * Sets site language to the given one.
     */
    public function set(string $lan) {
        if (in_array($lan, ['lv', 'en'])) {
            $prev = Session::get('locale');
            App::setLocale($lan); 
            Session::put('locale', $lan);

            $redirect_link = str_replace('/'.$prev, '/'.$lan, url()->previous());
            return redirect()->to($redirect_link);
        }
        return back();
    }
}
