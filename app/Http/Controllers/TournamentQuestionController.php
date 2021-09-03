<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\TournamentQuestionImport;

class TournamentQuestionController extends Controller
{
    //

    public function import(Request $request)
    {
        Excel::import(new QuestionImport, $request->file('bulk'));
        return back();

    }
}
