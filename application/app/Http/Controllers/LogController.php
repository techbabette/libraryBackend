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

        $response = [];
        $response['sortOptions'] = Log::sortOptions();
        $response['sortDefault'] = $sortDefault;

        //Filters
        if($request->get('since')){
            $logs->where('created_at', '>=', $request->get('since'));
        }

        if($request->get('before')){
            $logs->where('created_at', '<=', $request->get('before'));
        }

        //Sorting
        if($request->get('sortSelected')){
            $logs->sort($request->get('sortSelected'));
        }else{
            $logs->sort($sortDefault);
        }

        //Retrieval
        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('noPage')){
            $logs = $logs->get();
        }else{
            $logs = $logs->paginate($perPage);
        }

        $response['body'] = $logs;
        $response['message'] = 'Successfully retrieved logs';

        return response()->json($response, 200);
    }
}
