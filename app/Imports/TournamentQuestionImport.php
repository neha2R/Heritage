<?php

namespace App\Imports;

use App\TournamentQuestions;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TournamentQuestionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $tournament_id;
    public function __construct($tournament_id)
    {
        $this->tournament_id = $tournament_id;
       // dd($this->tournament_id);
    }
    public function model(array $row)
    {
            //dd($row);
     $row['tournament_id'] = $this->tournament_id;
        return new TournamentQuestions([
            //
     
                'question'     => $row['question'],
                'question_img'     => $row['question_img'],
                'keyword'     => $row['keyword'],
                'explanation'     => $row['explanation'],
                'answer'     => $row['answer'],
                'answer_img'     => $row['answer_img'],
                'option_1'     => $row['option_1'],
                'option_1_img'     => $row['option_1_img'],
                'option_2'     => $row['option_2'],
                'option_2_img'     => $row['option_2_img'],
                'option_3'     => $row['option_3'],
                'option_3_img'     => $row['option_3_img'],
                'option_4'     => $row['option_4'],
                'option_4_img'     => $row['option_4_img'],
                'tournament_id' => $row['tournament_id']
        
          

        ]);
    }
}
