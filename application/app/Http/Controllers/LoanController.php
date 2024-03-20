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

        $response = [];
        $response['sortOptions'] = Loan::sortOptions();
        $response['sortDefault'] = $sortDefault;

        $loans = Loan::query()->with(['book' => function ($query) {
            return $query->withTrashed();
        }]);

        //Filters

        //Early resposne if query param present
        if($request->get('allCount')){
            $booksLoaned = Loan::withTrashed()->count();
            $response['body'] = $booksLoaned;
            $response['message'] = 'Successfully got loan count';
            return response()->json($response, 200);
        }

        //Limit response for base case
        if(!$request->get('panel') && auth()->user()){
            $userId = auth()->user()->id;
            $loans->where('user_id', '=', $userId);
            $response['message'] = 'Successfully retrieved user loans';
        }

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
            $response['body'] = $booksLoaned;
            $response['message'] = 'Successfully got loan count';
            return response()->json($response, 200);
        }

        //Sort before retrieval, after filters
        if($request->get('sortSelected')){
            $loans->sort($request->get('sortSelected'));
        }else{
            $loans->sort($sortDefault);
        }

        //Selection
        if($request->get('panel')){
            $loans = $loans->with('user');
            $response['message'] = 'Successfully retrieved all loans';
        }

        //Retrieval
        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('noPage')){
            $loans = $loans->get();
        }else{
            $loans = $loans->paginate($perPage);
        }

        $response['body'] = $loans;
        return response()->json($response, 200);
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
