<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        $links = [];
        if (!auth()->user()) {
            $links = Link::whereHas('access_level', function ($query) {
                $query->where('access_level', '<=', 0);
            })->get();
            return response($links);
        }

        $links = auth()->user()->linksForUser();

        return response($links);
    }
}
