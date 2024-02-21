<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanExtendRequest;
use App\Http\Requests\LoanReturnRequest;
use App\Http\Requests\LoanStoreRequest;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request){
        $perPage = 5;
        $sortDefault = 'started_at_desc';

        $loans = Loan::query()->with('book');

        if($request->get('currentAndPrevious')){
            $loans->withTrashed();
        }

        if($request->get('late')){
            $loans->late();
        }

        if($request->get('previous')){
            $loans->onlyTrashed();
        }

        if($request->get('since')){
            $loans->where('created_at', '>=', $request->get('since'));
        }

        if($request->get('before')){
            $loans->where('created_at', '<=', $request->get('before'));
        }

        if($request->get('onlyCount')){
            $booksLoaned = $loans->count();
            return response()->json(['message' => 'Successfully got loan count', 'body' => $booksLoaned], 200);
        }

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        //Sort before retrieval, after filters
        if($request->get('sortSelected')){
            $loans->sort($request->get('sortSelected'));
        }else{
            $loans->sort($sortDefault);
        }

        $sortOptions = Loan::sortOptions();

        if($request->get('panel')){
            $loans = $loans->with('user')->with('book')->paginate($perPage);
            return response()->json(['message' => 'Successfully retrieved all loans', 'body' => $loans, 'sortOptions' => $sortOptions, 'sortDefault' => $sortDefault], 200);
        }

        $userId = auth()->user()->id;
        $loans->where('user_id', '=', $userId);

        $loans = $loans->paginate($perPage);

        return response()->json(['message' => 'Successfully retrieved user loans', 'body' => $loans, 'sortOptions' => $sortOptions, 'sortDefault' => $sortDefault], 200);
    }

    public function store(LoanStoreRequest $request){
        $bookId = $request->book_id;
        $userId = auth()->user()->id;

        $loanId = Loan::create(["user_id" => $userId, "book_id" => $bookId])->id;

        return response()->json(['message' => 'Successfully borrowed book', "body" => ["loan_id" => $loanId]], 200);
    }

    public function return(LoanReturnRequest $request){
        $loanId = $request->id;

        $loan = Loan::find($loanId);

        $loan->delete();

        return response()->json(['message' => 'Successfully returned book'], 200);
    }

    public function extend(LoanExtendRequest $request){
        $loanId = $request->id;

        $loan = Loan::find($loanId);

        $loan->end = Carbon::parse($loan->end)->addDays(20);
        $loan->extended = true;
        $loan->save();

        return response()->json(['message' => 'Successfully extended loan'], 200);
    }
}
