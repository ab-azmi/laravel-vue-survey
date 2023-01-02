<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SurveyAnswerResource;
use App\Http\Resources\SurveyResourceDashboard;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        //Total number of surveys
        $total = Survey::query()->where('user_id', $user->id)->count();

        //latest survey
        $latest = Survey::query()->where('user_id', $user->id)->latest()->first();

        //total number of answers
        $totalAnswers = SurveyAnswer::with('survey')
            ->whereRelation('user', 'users.id', $user->id)->count();

        //latest 5 answers
        $latestAnswers = SurveyAnswer::with('survey')
            ->whereRelation('user', 'users.id', $user->id)
            ->latest()->take(5)->get();

        return [
            'totalSurveys' => $total,
            'latestSurvey' => $latest ? new SurveyResourceDashboard($latest) : null,
            'totalAnswers' => $totalAnswers,
            'latestAnswers' => SurveyAnswerResource::collection($latestAnswers),
        ];
    }
}
