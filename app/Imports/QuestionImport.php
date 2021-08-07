<?php

namespace App\Imports;

use App\Question;
use App\DifficultyLevel;
use App\Domain;
use App\AgeGroup;
use App\QuestionsSetting;
// use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;



class QuestionImport implements ToCollection, WithStartRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if($row[5]!="")
            {
                $type=pathinfo($row[5], PATHINFO_EXTENSION);
            }
            else
            {
                $type="N/A";
            }
            if($row[10]=="")
            {
                $option1="";
            }
            else
            {
                $option1=$row[10];
            }
            if($row[11]=="")
            {
                $option_media1="";
            }
            else
            {
                $option_media1=$row[11];
            }
            if($row[12]=="")
            {
                $option2="";
            }
            else
            {
                $option2=$row[12];
            }
            if($row[13]=="")
            {
                $option_media2="";
            }
            else
            {
                $option_media2=$row[13];
            }
            if($row[14]=="")
            {
                $option3="";
            }
            else
            {
                $option3=$row[14];
            }
            if($row[15]=="")
            {
                $option_media3="";
            }
            else
            {
                $option_media3=$row[15];
            }
            if($row[16]=="")
            {
                $option4="";
            }
            else
            {
                $option4=$row[16];
            }
            if($row[17]=="")
            {
                $option_media4="";
            }
            else
            {
                $option_media4=$row[17];
            }
            if($row[9]=="")
            {
                $answer_image="";
            }
            else
            {
                $answer_image=$row[9];
            }
            if($row[7]=="")
            {
                $explanation="";
            }
            else
            {
                $explanation=$row[7];
            }
            $Question=Question::create([
                'question' => $row[4],
                'question_media' => $row[5],
                'option1' => $option1,
                'option1_media' => $option_media1,
                'option2'=>$option2, 	
                'option2_media'=>$option_media2,	
                'option3'=>$option3,
                'option3_media'=> $option_media3,	
                'option4'=> $option4,
                'option4_media'=> $option_media4,
                'why_right'=>$explanation,
                'why_right_media'=> $answer_image,
                'right_option'=> $row[8],
                'hint'=> $row[6],
                'question_media_type'=>$type 
            ]);
            
            if($Domain=Domain::where('name',trim($row[2]))->first())
            {
                $Domain=$Domain->id;
            }
            else
            {
                $Domain='1';
            }
        
           
            if($DifficultyLevel=DifficultyLevel::where('name',trim($row[3]))->first())
            {
                $DifficultyLevel=$DifficultyLevel->id;
            }
            else
            {
                $DifficultyLevel='1';
            }
            if($AgeGroup=AgeGroup::where('name',trim($row[1]))->first())
            {
                $AgeGroup=$AgeGroup->id;
            }
            else
            {
                $AgeGroup='1';
            }
            QuestionsSetting::create([
                'question_id'=>$Question->id,
                'domain_id' =>$Domain,
                'difficulty_level_id' => $DifficultyLevel,
                'age_group_id' => $AgeGroup,
                'subdomain_id'=>'1',
                'name'=>'parent'
            ]);
        }
    }
    public function startRow(): int
    {
        return 2;
    }
}
