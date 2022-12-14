<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyAnswerRequest;
use App\Http\Resources\SurveyResource;
use App\Models\Survey;
use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionAnswer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return SurveyResource::collection(Survey::where('user_id', $user->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreSurveyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSurveyRequest $request)
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }

        $result = Survey::create($data);

        //Create new questions
        foreach ($data['questions'] as $question) {
            $question['survey_id'] = $result->id;
            $this->createQuestion($question);
        }

        return new SurveyResource($result);
    }

    private function saveImage($image)
    {
        //check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            //takeout the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1);
            //get file extension
            $type = strtolower($type[1]); // jpg, png, gif
            //check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }

            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('Invalid image');
        }

        $dir = 'images/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;

        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true, true);
        }
        file_put_contents($relativePath, $image);

        return $relativePath;
    }

    private function createQuestion($data)
    {
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data, [
            'question' => 'required|string',
            'type' => [
                'required',
                Rule::in([
                    Survey::TYPE_TEXT,
                    Survey::TYPE_TEXTAREA,
                    Survey::TYPE_RADIO,
                    Survey::TYPE_CHECKBOX,
                    Survey::TYPE_SELECT,
                ])],
            'description' => 'nullable|string',
            'data' => 'present',
            'survey_id' => 'exists:surveys,id',
        ]);

        return SurveyQuestion::create($validator->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Survey $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey, Request $request)
    {
        $user = $request->user();
        if ($survey->user_id !== $user->id) {
            return abort(403, 'Unauthorized action.');
        }
        return new SurveyResource($survey);
    }

    public function getSurveyBySlug(Survey $survey)
    {
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateSurveyRequest $request
     * @param \App\Models\Survey $survey
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;

            //if there is an old image
            if ($survey->image) {
                $absolutePath = public_path($survey->image);
                File::delete($absolutePath);
            }
        }

        $survey->update($data);

        //get ids of questions
        $existingIds = $survey->questions()->pluck('id')->toArray();

        //get ids as plain array of new questions
        $newIds = Arr::pluck($data['questions'], 'id');

        //find questions to delete
        $toDelete = array_diff($existingIds, $newIds);

        //find questions to add
        $toAdd = array_diff($newIds, $existingIds);

        //delete questions by $todelete array
        SurveyQuestion::destroy($toDelete);

        //create new questions
        foreach ($data['questions'] as $question) {
            if (in_array($question['id'], $toAdd)) {
                $question['survey_id'] = $survey->id;
                $this->createQuestion($question);
            }
        }

        //update existing questions
        $questionMap = collect($data['questions'])->keyBy('id');
        foreach ($survey->questions as $question) {
            if (isset($questionMap[$question->id])) {
                $this->updateQuestion($question, $questionMap[$question->id]);
            }
        }

        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Survey $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey, Request $request)
    {
        $user = $request->user();
        if ($survey->user_id !== $user->id) {
            return abort(403, 'Unauthorized action.');
        }

        $survey->delete();

        //if there is an image
        if ($survey->image) {
            $absolutePath = public_path($survey->image);
            File::delete($absolutePath);
        }

        return response()->noContent();
    }

    private function updateQuestion(SurveyQuestion $question, $data)
    {
        if (is_array($data['data'])) {
            //encode data to json
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data, [
            'id' => 'exists:survey_questions,id',
            'question' => 'required|string',
            'type' => [
                'required',
                Rule::in([
                    Survey::TYPE_TEXT,
                    Survey::TYPE_TEXTAREA,
                    Survey::TYPE_RADIO,
                    Survey::TYPE_CHECKBOX,
                    Survey::TYPE_SELECT,
                ])],
            'description' => 'nullable|string',
            'data' => 'present',
        ]);

        return $question->update($validator->validated());
    }

    public function storeAnswer(StoreSurveyAnswerRequest $request, Survey $survey)
    {
        $validated = $request->validated();
        $surveyAnswer = SurveyAnswer::create([
            'survey_id' => $survey->id,
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
        ]);

        foreach ($validated['answers'] as $questionId => $answer) {
            $question = SurveyQuestion::where(['id' => $questionId, 'survey_id' => $survey->id])->get();
            if (!$question) {
                return response('invalid question id', 400);
            }

            $data = [
                'survey_answer_id' => $surveyAnswer->id,
                'survey_question_id' => $questionId,
                'answer' => is_array($answer) ? json_encode($answer) : $answer,
            ];

            SurveyQuestionAnswer::create($data);
        }
        return response('success', 201);
    }

}
