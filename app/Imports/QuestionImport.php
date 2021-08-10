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
use Maatwebsite\Excel\Concerns\WithHeadingRow;



class QuestionImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    { 
        
        
        foreach ($rows as $row) 
        {
           
            if($row['question_img']!="")
            {
                $type=pathinfo($row['question_img'], PATHINFO_EXTENSION);
            }
            else
            {
                $type="N/A";
            }
            if($row['option_1']=="")
            {
                $option1="";
            }
            else
            {
                $option1=$row['option_1'];
            }
            if($row['option_1_img']=="")
            {
                $option_media1="";
            }
            else
            {
                $option_media1=$row['option_1_img'];
            }
            if($row['option_2']=="")
            {
                $option2="";
            }
            else
            {
                $option2=$row['option_2'];
            }
            if($row['option_2_img']=="")
            {
                $option_media2="";
            }
            else
            {
                $option_media2=$row['option_2_img'];
            }
            if($row['option_3']=="")
            {
                $option3="";
            }
            else
            {
                $option3=$row['option_3'];
            }
            if($row['option_3_img']=="")
            {
                $option_media3="";
            }
            else
            {
                $option_media3=$row['option_3_img'];
            }
            if($row['option_4']=="")
            {
                $option4="";
            }
            else
            {
                $option4=$row['option_4'];
            }
            if($row['option_4_img']=="")
            {
                $option_media4="";
            }
            else
            {
                $option_media4=$row['option_4_img'];
            }
            if($row['answer_img']=="")
            {
                $answer_image="";
            }
            else
            {
                $answer_image=$row['answer_img'];
            }
            if($row['explanation']=="")
            {
                $explanation="";
            }
            else
            {
                $explanation=$row['explanation'];
            }
            $Question=Question::create([
                'question' => $row['question'],
                'question_media' => $row['question_img'],
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
                'right_option'=> $row['answer'],
                'hint'=> $row['keyword'],
                'question_media_type'=>$type 
            ]);
            
            if($Domain=Domain::where('name',trim(strtolower($row['domain'])))->first())
            {
                $Domain=$Domain->id;
            }
            else
            {
                $Domain='1';
            }
        
           
            if($DifficultyLevel=DifficultyLevel::where('name',trim(strtolower($row['difficulty_name'])))->first())
            {
                $DifficultyLevel=$DifficultyLevel->id;
            }
            else
            {
                $DifficultyLevel='1';
            }
            if($AgeGroup=AgeGroup::where('name',trim(strtolower($row['age_group'])))->first())
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
  
    public function headingRow(): int
    {
        return 1;
    }
}
