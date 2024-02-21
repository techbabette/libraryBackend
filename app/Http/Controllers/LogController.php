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
        $sortSelected = 'created_at_desc';

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('sortSelected')){
            $sortSelected = $request->get('sortSelected');
        }

        $sortOptions = Log::sortOptions();
        $logs->sort($sortSelected);

        $logs = $logs->paginate($perPage);

        return response()->json(['message' => 'Successfully retrieved logs', 'body' => $logs, 'sortOptions' => $sortOptions], 200);
    }
}
