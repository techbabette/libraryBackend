<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExtendLoanRequest;
use App\Http\Requests\ReturnLoanRequest;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request){
        $perPage = 5;

        $loans = Loan::query();

        if(!$request->get('onlyCount')){
            $userId = auth()->user()->id;
            $loans->where('user_id', '=', $userId)->with('book');
        }

        if($request->get('current')){
            $loans->where('end', '>=', date('Y-m-d'))->whereNull('returned');
        }

        if($request->get('previous')){
            $loans->whereNotNull('returned');
        }

        if($request->get('since')){
            $loans->whereDate('start', '>=', $request->get('since'));
        }

        if($request->get('before')){
            $loans->whereDate('start', '<=', $request->get('before'));
        }

        if($request->get('onlyCount')){
            $booksLoaned = $loans->count();
            return response()->json(['message' => 'Successfully got loan count', 'body' => $booksLoaned], 200);
        }

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        $loans = $loans->paginate($perPage);

        return response()->json(['message' => 'Successfully retrieved user loans', 'body' => $loans], 200);
    }

    public function return(ReturnLoanRequest $request){
        $loanId = $request->id;

        $loan = Loan::find($loanId);

        $loan->returned = now();
        $loan->save();

        return response()->json(['message' => 'Successfully returned book'], 200);
    }

    public function extend(ExtendLoanRequest $request){
        $loanId = $request->id;

        $loan = Loan::find($loanId);

        $loan->end = Carbon::parse($loan->end)->addDays(20);
        $loan->extended = true;
        $loan->save();

        return response()->json(['message' => 'Successfully extended loan'], 200);
    }
}
