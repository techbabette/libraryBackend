<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        $userAccessLevel = -1;
        if(auth()->user()){
            $userAccessLevel = auth()->user()->access_level->access_level;
        }

        $links = Link::getLinksForAccessLevel($userAccessLevel);

        return response($links);
    }

    public function store(){

    }
}
