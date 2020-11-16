<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;

class ShortUrlController extends Controller
{
    public function route(Request $request, ShortUrl $shortUrl) {
    	$shortUrl->clicks++;
    	$shortUrl->save();
    	return redirect($shortUrl->destination);
    }
}
