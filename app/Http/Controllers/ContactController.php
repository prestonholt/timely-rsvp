<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function search(Request $request) {
    	$q = $request->input('q', null);
    	if (!$q)
    		return response()->json([]);

    	return $request->user()->contacts()->where('name', 'like', '%' . $q . '%')->limit(5)->get()->toJson();
    }
}
