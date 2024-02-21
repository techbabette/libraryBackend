<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request){
        $logs = Log::query();

        $perPage = 5;
        $sortDefault = 'created_at_desc';

        if($request->get('since')){
            $logs->where('created_at', '>=', $request->get('since'));
        }

        if($request->get('before')){
            $logs->where('created_at', '<=', $request->get('before'));
        }

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('sortSelected')){
            $logs->sort($request->get('sortSelected'));
        }else{
            $logs->sort($sortDefault);
        }

        $sortOptions = Log::sortOptions();

        $logs = $logs->paginate($perPage);

        return response()->json(['message' => 'Successfully retrieved logs', 'body' => $logs, 'sortOptions' => $sortOptions, 'sortDefault' => $sortDefault], 200);
    }
}
