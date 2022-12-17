<?php

namespace App\Http\Controllers;

use App\Http\Resources\SurveyAnswerResource;
use App\Http\Resources\SurveyResourceDashboard;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        //Total number of surveys
        $total = Survey::query()->where('user_id', $user->id)->count();

        //latest survey
        $latest = Survey::query()->where('user_id', $user->id)->latest()->first();

        //total number of answers
        $totalAnswers = SurveyAnswer::with(['survey' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->count();

        //latest 5 answers
        $latestAnswers = SurveyAnswer::with(['survey' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->latest()->take(5)->get();

        return [
            'totalSurveys' => $total,
            'latestSurvey' => $latest ? new SurveyResourceDashboard($latest) : null,
            'totalAnswers' => $totalAnswers,
            'latestAnswers' => SurveyAnswerResource::collection($latestAnswers),
        ];
    }
}
