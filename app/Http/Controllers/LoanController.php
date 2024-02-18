<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request){
        $perPage = 5;

        $userId = auth()->user()->id;

        $loans = Loan::where('user_id', '=', $userId)->with('book');

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        $loans = $loans->paginate($perPage);

        return response()->json(['message' => 'Successfully retrieved user loans', 'body' => $loans], 200);
    }
}
