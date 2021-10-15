<?php

namespace App\Http\Controllers;
use App\AgeGroup;
use App\Attempt;
use App\DifficultyLevel;
use App\Domain;
use App\Imports\QuestionImport;
use App\Question;
use App\QuestionsSetting;
use App\QuizDomain;
use App\QuizQuestion;
use App\QuizSpeed;
use App\QuizType;
use App\Subdomain;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::OrderBy('id', 'DESC')->get();
        $age_groups = AgeGroup::OrderBy('id', 'DESC')->get();
        $domains = Domain::OrderBy('id', 'DESC')->get();
        $subdomains = Subdomain::OrderBy('id', 'DESC')->get();
        $diffulcitylevels = DifficultyLevel::OrderBy('id', 'DESC')->get();

        return view('question.list', compact('questions', 'age_groups', 'domains', 'diffulcitylevels', 'subdomains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $age_groups = AgeGroup::OrderBy('id', 'DESC')->get();
        $domains = Domain::OrderBy('id', 'DESC')->get();
        $sub_domains = Sundomain::OrderBy('id', 'DESC')->get();
        $diffulcitylevels = DifficultyLevel::OrderBy('id', 'DESC')->get();

        return view('question.add', compact('age_groups', 'domains'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate([
            'question' => 'required',
            'option1' => 'required',
            'option2' => 'required',
            'option3' => 'required',
            'option4' => 'required',
            'right_option' => 'required',
            'domain_id' => 'required',
            'subdomain_id' => 'required',
            'age_group_name' => 'required',
            'difficulty_level_name' => 'required',
            //  'question_media'=>'mimes:mp4,mov,ogg,jpeg,jpg,png,gif|required|max:10000',
        ]);
        // $option1_media = '';
        // $option2_media = '';
        // $option3_media = '';
        // $option4_media = '';
        $question_media = '';
        $type = '0';
        $mediadata=[];
        if ($request->has('question_media')) {
            $foldername = 'question';
            $file = $request->file('question_media');

            $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
            $videomimes = ['video/mp4']; //Add more mimes that you want to support
            $audiomimes = ['audio/mpeg']; //Add more mimes that you want to support

            $question_media = $file->store('question', 'public');
          
            if (in_array($file->getMimeType(), $imagemimes)) {
                $type = '1';
                $mediadata =getimagesize($request->file('question_media'));

            }

            //validate audio
            if (in_array($file->getMimeType(), $audiomimes)) {
                $type = '2';
                $mediadata[] =$request->file('question_media')->getSize();

            }

            //Validate video
            if (in_array($file->getMimeType(), $videomimes)) {
                $type = '3';
                $mediadata[] =$request->file('question_media')->getSize();

            }

        }
        // if ($request->has('option1_media')) {
        //     $foldername = 'option1';
        //     $option1_media = $request->file('option1_media')->store($foldername, 'public');
        // }
        // if ($request->has('option2_media')) {
        //     $foldername = 'option2';
        //     $option2_media = $request->file('option2_media')->store($foldername, 'public');
        // }
        // if ($request->has('option3_media')) {
        //     $foldername = 'option3';
        //     $option3_media = $request->file('option3_media')->store($foldername, 'public');
        // }
        // if ($request->has('option4_media')) {
        //     $foldername = 'option4';
        //     $option4_media = $request->file('option4_media')->store($foldername, 'public');
        // }

        $data = new Question;
        $data->question = $request->question;
        $data->option1 = $request->option1;
        $data->option2 = $request->option2;
        $data->option3 = $request->option3;
        $data->option4 = $request->option4;
        $data->question_media = $question_media;
        // $data->option1_media = $option1_media;
        // $data->option2_media = $option2_media;
        // $data->option3_media = $option3_media;
        // $data->option4_media = $option4_media;
        $data->right_option = $request->right_option;
        $data->question_media_type = "." . $request->question_media_type;
        $data->type = $type;
       $data->attachment_details= json_encode($mediadata);
        $data->save();

        $quessetting = new QuestionsSetting;
        $quessetting->question_id = $data->id;
        $quessetting->age_group_id = $request->age_group_name;
        $quessetting->difficulty_level_id = $request->difficulty_level_name;
        $quessetting->domain_id = $request->domain_id;
        $quessetting->subdomain_id = $request->subdomain_id;
        $quessetting->name = "parent";
        $quessetting->save();

        if (isset($request->age_group_id)) {
            foreach ($request->age_group_id as $key => $age) {
                if (!QuestionsSetting::where('question_id', $data->id)->where('age_group_id', $age)->where('difficulty_level_id', $request->difficulty_level_id[$key])->first()) {

                    $quessetting = new QuestionsSetting;
                    $quessetting->question_id = $data->id;
                    $quessetting->age_group_id = $age;
                    $quessetting->difficulty_level_id = $request->difficulty_level_id[$key];
                    $quessetting->domain_id = $request->domain_id;
                    $quessetting->subdomain_id = $request->subdomain_id;
                    $quessetting->save();

                }

            }
        }

        if ($data->id) {
            return redirect('admin/question')->with(['success' => 'Question saved successfully', 'model' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::whereId($id)->first();
        $age_groups = AgeGroup::OrderBy('id', 'DESC')->get();
        $domains = Domain::OrderBy('id', 'DESC')->get();
        $subdomains = Subdomain::OrderBy('id', 'DESC')->get();
        $diffulcitylevels = DifficultyLevel::OrderBy('id', 'DESC')->get();

        return view('question.edit_question', compact('question', 'age_groups', 'domains', 'diffulcitylevels', 'subdomains'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {

        // dd($request);
        $validatedData = $request->validate([
            'question' => 'required',
            'option1' => 'required',
            'option2' => 'required',
            'option3' => 'required',
            'option4' => 'required',
            'right_option' => 'required',
            'domain_id' => 'required',
            'subdomain_id' => 'required',
            'age_group_name' => 'required',
            'difficulty_level_name' => 'required',
        ]);
        // $option1_media = '';
        // $option2_media = '';
        // $option3_media = '';
        // $option4_media = '';
        $question_media = '';
        $type = '';
   
        if ($request->has('question_media')) {
            $foldername = 'question';

            if ($request->question_media_old) {

                unlink(storage_path('app/public/' . $request->question_media_old));
            }
            $file = $request->file('question_media');

            $imagemimes = ['image/png', 'image/jpg', 'image/jpeg', 'image_gif']; //Add more mimes that you want to support
            $videomimes = ['video/mp4']; //Add more mimes that you want to support
            $audiomimes = ['audio/mpeg', 'audio/mp3']; //Add more mimes that you want to support

            $question_media = $file->store('question', 'public');
        
            $mediadata =[];
            if (in_array($file->getMimeType(), $imagemimes)) {
                $type = '1';
                $mediadata =getimagesize($request->file('question_media'));

            }

            //validate audio
            if (in_array($file->getMimeType(), $audiomimes)) {
                $type = '2';
                $mediadata[] =$request->file('question_media')->getSize();

            }

            //Validate video
            if (in_array($file->getMimeType(), $videomimes)) {
                $type = '3';
                $mediadata[] =$request->file('question_media')->getSize();

            }

            $question_media = $request->file('question_media')->store($foldername, 'public');
        } else {
            $question_media = $request->question_media_old;
        }
        // if ($request->has('option1_media')) {
        //     $foldername = 'option1';
        //     if (file_exists(storage_path('app/public/' . $request->option1_media_old))) {
        //         unlink(storage_path('app/public/' . $request->option1_media_old));
        //     }
        //     $option1_media = $request->file('option1_media')->store($foldername, 'public');
        // } else {
        //     $option1_media = $request->option1_media_old;
        // }
        // if ($request->has('option2_media')) {
        //     $foldername = 'option2';
        //     if (file_exists(storage_path('app/public/' . $request->option2_media_old))) {
        //         unlink(storage_path('app/public/' . $request->option2_media_old));
        //     }
        //     $option2_media = $request->file('option2_media')->store($foldername, 'public');
        // } else {
        //     $option2_media = $request->option2_media_old;
        // }
        // if ($request->has('option3_media')) {
        //     $foldername = 'option3';
        //     if (file_exists(storage_path('app/public/' . $request->option3_media_old))) {
        //         unlink(storage_path('app/public/' . $request->option3_media_old));
        //     }
        //     $option3_media = $request->file('option3_media')->store($foldername, 'public');
        // } else {
        //     $option3_media = $request->option3_media_old;
        // }
        // if ($request->has('option4_media')) {
        //     $foldername = 'option4';
        //     if (file_exists(storage_path('app/public/' . $request->option4_media_old))) {
        //         unlink(storage_path('app/public/' . $request->option4_media_old));
        //     }
        //     $option4_media = $request->file('option4_media')->store($foldername, 'public');
        // } else {
        //     $option4_media = $request->option4_media_old;
        // }

        $data = Question::whereId($question->id)->first();
        $data->question = $request->question;
        $data->option1 = $request->option1;
        $data->option2 = $request->option2;
        $data->option3 = $request->option3;
        $data->option4 = $request->option4;
        $data->question_media = $question_media;
        // $data->option1_media = $option1_media;
        // $data->option2_media = $option2_media;
        // $data->option3_media = $option3_media;
        // $data->option4_media = $option4_media;
        $data->right_option = $request->right_option;
        $data->question_media_type = "." . $request->question_media_type_old;
        if($request->has('question_media'))
        {
            
            $data->type = $type;
            $data->attachment_details= json_encode($mediadata);
        }
        $data->save();

        if (QuestionsSetting::where('question_id', $data->id)->first()) {
            QuestionsSetting::where('question_id', $data->id)->delete();
        }

        $quessetting = new QuestionsSetting;
        $quessetting->question_id = $data->id;
        $quessetting->age_group_id = $request->age_group_name;
        $quessetting->difficulty_level_id = $request->difficulty_level_name;
        $quessetting->domain_id = $request->domain_id;
        $quessetting->subdomain_id = $request->subdomain_id;
        $quessetting->name = "parent";
        $quessetting->save();

        if (isset($request->age_group_id)) {

            foreach ($request->age_group_id as $key => $age) {

                if (!QuestionsSetting::where('question_id', $data->id)->where('age_group_id', $age)->where('difficulty_level_id', $request->difficulty_level_id[$key])->first()) {
                    $quessetting = new QuestionsSetting;
                    $quessetting->question_id = $data->id;
                    $quessetting->age_group_id = $age;
                    $quessetting->difficulty_level_id = $request->difficulty_level_id[$key];
                    $quessetting->domain_id = $request->domain_id;
                    $quessetting->subdomain_id = $request->subdomain_id;
                    $quessetting->save();
                }

            }
        }
        if ($data->id) {
            return redirect('admin/question')->with(['success' => 'Question updated successfully', 'model' => 'model show']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {

        $question = Question::find($question->id);

        if ($question) {
            $question->delete();
            $question->questionsetting()->delete();
        }

        if ($question->id) {
            return redirect()->back()->with(['success' => 'Question Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    public function question(Request $request)
    {

        $quiz = Attempt::find($request->quiz_id);
        if (empty($quiz)) {
            return response()->json(['status' => 200, 'message' => 'Quiz not found', 'data' => '']);
        }
        $user = User::find($quiz->user_id);
        $speed = QuizSpeed::find($quiz->quiz_speed_id);
        $quiz_type = QuizType::find($quiz->quiz_type_id);

        $domains = QuizDomain::where('attempts_id', $quiz->id)->first();
        $diff = DifficultyLevel::find($quiz->difficulty_level_id);
        $age_group = AgeGroup::where('from', '<=', $user->age)->where('to', '>=', $user->age)->first();

        if (empty($age_group)) {
            $age_group = AgeGroup::where('from', '>=', $user->age)->latest();
        }
        $domains = (explode(",", $domains));

        $quesdis = strtolower($diff->name);
    
        switch ($quesdis) {
            case "beginner":
                //Easy level question distribution
                // Easy    (75% E, 25% H/I)
                // dd($speed->no_of_question);
                // dd($age_group->id);
                $dis1 = round(($speed->no_of_question / 100) * 75);
                
                $question_id1 = QuestionsSetting::inRandomOrder()->where('age_group_id', $age_group->id)
                    ->where('difficulty_level_id', $diff->id)->whereIn('domain_id', $domains)->limit($dis1)->pluck('question_id')->toArray();

                $dis2 = round(($speed->no_of_question - $dis1) / 2);
                $question_id2 = QuestionsSetting::inRandomOrder()->where('age_group_id', $age_group->id)
                    ->where('difficulty_level_id', 2)->whereIn('domain_id', $domains)->limit($dis2)->pluck('question_id')->toArray();
       
                $dis3 = round($speed->no_of_question - ($dis1 + $dis2));

                // dd($dis1,$dis2,$dis3);

                $question_id3 = QuestionsSetting::inRandomOrder()->where('age_group_id', $age_group->id)
                    ->where('difficulty_level_id', 3)->whereIn('domain_id', $domains)->limit($dis3)->pluck('question_id')->toArray();

                //  dd($question_id1,$question_id2,$question_id3);

                $question_ids = array_merge($question_id1, $question_id2, $question_id3);

                // dd($question_id1, $dis1, $dis2, $speed->no_of_question);
                // $question_ids = $question_ids->get()->toArray();
                break;
            case "intermediate":
                //Intermediate level question distribution
                // Easy    (75% I, 25% H/E)
                $dis1 = round(($speed->no_of_question / 100) * 75);
                $question_id1 = QuestionsSetting::inRandomOrder()->where('age_group_id', $age_group->id)
                    ->where('difficulty_level_id', $diff->id)->whereIn('domain_id', $domains)->limit($dis1)->pluck('question_id')->toArray();

                $dis2 = round(($speed->no_of_question - $dis1) / 2);
                // dd($question_id1,$age_group->id,$diff->id,$dis1);
                $question_id2 = QuestionsSetting::inRandomOrder()->where('age_group_id', $age_group->id)
                    ->where('difficulty_level_id', 1)->whereIn('domain_id', $domains)->limit($dis2)->pluck('question_id')->toArray();

                $dis3 = round($speed->no_of_question - ($dis1 + $dis2));

                $question_id3 = QuestionsSetting::inRandomOrder()->where('age_group_id', $age_group->id)
                    ->where('difficulty_level_id', 3)->whereIn('domain_id', $domains)->limit($dis3)->pluck('question_id')->toArray();

                $question_ids = array_merge($question_id1, $question_id2, $question_id3);
           
                // $question_ids->get()->toArray();
                break;
            case "advance":
                $dis1 = round(($speed->no_of_question / 100) * 75);
                $question_id1 = QuestionsSetting::inRandomOrder()->where('age_group_id', $age_group->id)
                    ->where('difficulty_level_id', $diff->id)->whereIn('domain_id', $domains)->limit($dis1)->pluck('question_id')->toArray();

                $dis2 = round(($speed->no_of_question - $dis1) / 2);
                $question_id2 = QuestionsSetting::inRandomOrder()->where('age_group_id', $age_group->id)
                    ->where('difficulty_level_id', 2)->whereIn('domain_id', $domains)->limit($dis2)->pluck('question_id')->toArray();

                $dis3 = round($speed->no_of_question - ($dis1 + $dis2));

                $question_id3 = QuestionsSetting::inRandomOrder()->where('age_group_id', $age_group->id)
                    ->where('difficulty_level_id', 1)->whereIn('domain_id', $domains)->limit($dis3)->pluck('question_id')->toArray();

                $question_ids = array_merge($question_id1, $question_id2, $question_id3);
                // $question_ids->get()->toArray();
                break;
            default:
                $dis1 = $speed->no_of_question;
                $question_ids = QuestionsSetting::inRandomOrder()->where('age_group_id', $age_group->id)
                    ->where('difficulty_level_id', $diff->id)->whereIn('domain_id', $domains)->limit($dis1)->pluck('question_id')->toArray();
                // $question_ids->get()->toArray();
        }

        // if (count($question_ids) < $speed->no_of_question) {
        //     $dis3 = $speed->no_of_question - count($question_ids);

        //     $question_id2 = QuestionsSetting::inRandomOrder()->whereIn('question_id', '!=', $question_ids)->limit($dis3)->pluck('question_id')->toArray();

        //     $question_ids = array_merge($question_id2, $question_ids);
        // }

        // print_r($question_ids);exit;
        shuffle($question_ids);

        $data = [];
       
        $data['quiz_type'] = $quiz_type->name;
        if ($speed->quiz_speed_type == 'single') {
            $data['time'] = $speed->duration;
            $data['whole_quiz_time'] = '0';
            $data['total_question'] = count($question_ids);
            $data['total_question_in_quiz'] = $speed->no_of_question;
            // foreach ($questions as $question) {
            //     // $id = $question->questionsettingapi->difficulty_level_id;
            //     // $time = $diff->where('id', $id)->first('time_per_question');
            //     $question['time'] = $speed->duration;
            //     // unset($question['questionsettingapi']);
            // }
        }
        if ($speed->quiz_speed_type == 'all') {
            $data['whole_quiz_time'] = '1';
            $data['time'] = $speed->duration;
        }

       

        $quizQuestions = QuizQuestion::where('attempts_id',$request->quiz_id)->latest()->first();
     
        if (empty($quizQuestions)) {
            $questions = Question::select('id', 'question', 'question_media', 'option1', 'option1_media', 'option2', 'option2_media', 'option3', 'option3_media', 'option4', 'option4_media', 'why_right', 'right_option', 'hint', 'question_media_type','type')->whereIn('id', $question_ids)->get(); 

            $quizques = new QuizQuestion;
            $quizques->attempts_id = $request->quiz_id;
            $quizques->questions = implode(",", $question_ids);
            $quizques->total = count($question_ids);
            $quizques->save();
        } 
        else{
            $ques = explode(",", $quizQuestions->questions);

            $questions = Question::select('id', 'question', 'question_media', 'option1', 'option1_media', 'option2', 'option2_media', 'option3', 'option3_media', 'option4', 'option4_media', 'why_right', 'right_option', 'hint','attachment_details','type')->whereIn('id', $ques)->get();
          
        }
        $response=[];
 
        foreach($questions as $que){
          
        $quesdata['id'] = $que->id;
        $quesdata['question'] = $que->question;
        if($que->question_media != null){
            $quesdata['question_media'] = url('/storage').'/'.$que->question_media;
         
          
            if($que->type=='1'){
                $detail = (array)json_decode($que->attachment_details);
                $quesdata['width']  =$detail['0'];
                $quesdata['height']  =$detail['1'];

            }
        }else{
            $quesdata['question_media'] = '';
            $quesdata['width']  ='';
            $quesdata['height']  ='';
        }
        
        $quesdata['option1'] = $que->option1;
        $quesdata['option1_media'] = $que->option1_media;
        $quesdata['option2'] = $que->option2;
        $quesdata['option2_media'] = $que->option2_media;
        $quesdata['option3'] = $que->option3;
        $quesdata['option3_media'] = $que->option3_media;
        $quesdata['option4'] = $que->option4;
        $quesdata['option4_media'] = $que->option4_media;
        $quesdata['right_option'] = $que->right_option;
        $quesdata['hint'] = $que->hint;
        $quesdata['question_media_type'] = $que->type;
        $quesdata['why_right'] = $que->why_right;
        $quesdata['type'] = $que->type;


        $response[] =$quesdata;
        }  

        $data['question'] = $response;
        $quiz->started_at= date('Y-m-d h:i:s');
        $quiz->save();

        return response()->json(['status' => 200, 'message' => 'Quiz Speed data', 'data' => $data]);

    }

    public function import(Request $request)
    {
        Excel::import(new QuestionImport, $request->file('bulk'));
        return back();

    }

    public function question_media(Request $request)
    {
        $quizQuestions = QuizQuestion::where('attempts_id',$request->quiz_id)->latest()->first();
        if (empty($quizQuestions)) {
            return response()->json(['status' => 200, 'message' => 'Quiz not found', 'data' => '']);
        }

        $ques = explode(",", $quizQuestions->questions);

        $questions = Question::select('question_media')->whereIn('id', $ques)->where('question_media','!=',null)->get();
        $response=[];
        foreach($questions as $que){
          
        $response[] = url('/storage').'/'.$que->question_media;


        }
        return response()->json(['status' => 200, 'message' => 'Question media data', 'data' => $response]);
    }
}
