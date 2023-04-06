<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;
use App\Http\Resources\SurveyResource;

use App\Http\Resources\SurveyAnswerResource;
use App\Http\Resources\SurveyResourceDashboard;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Total number of survey

        $total = Survey::query()
        ->where('user_id' , $user->id)
        ->count();

        // latest survey

        $latest = Survey::query()
        ->where('user_id' , $user->id)
        ->latest('created_at')
        ->first();

        // total number of answers

        $totalAnswers = SurveyAnswer::query()
        ->join('surveys' , 'survey_answers.survey_id' , '=' , 'survey_id')
        ->where('user_id' , $user->id)
        ->count();

        // latest 5 surveys
        $latestAnswers = SurveyAnswer::query()
        ->join('surveys' , 'survey_answers.survey_id' , '=' , 'survey_id')->where('surveys.user_id' , $user->id)
        ->orderBy('end_date' , 'DESC')
        ->limit(5)
        ->getModels('survey_answers.*');

        return[
            "totalSurveys" =>$total,
            "latestSurvey" => $latest ? new SurveyResourceDashboard($latest) : null ,
            "totalAnswers" => $totalAnswers,
            "latestAnswers" => SurveyAnswerResource::collection($latestAnswers)
        ];
    }
}
