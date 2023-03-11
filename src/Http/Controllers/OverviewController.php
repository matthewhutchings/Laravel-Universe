<?php

namespace Laravel\Telescope\Http\Controllers;

use App\Models\UniverseDashboards;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Telescope\Telescope;
use Illuminate\Support\Facades\DB;
use Orhanerday\OpenAi\OpenAi;

class OverviewController extends Controller
{
    /**
     * Display the Telescope view.
     *
     * @return \Illuminate\Http\Response
     */
    public function request(Request $request)
    {

        $modelName = "App\Models\\" . $request->model; // The string that contains the Eloquent model name
        $loadedModel = resolve($modelName); // Load the Eloquent model using the string

        $sqlQuestion = $request->sql;

        if (!$request->sql) {
            /*        $fillableAttributes = implode(',', $loadedModel->getFillable()); // Get fillable attributes and implode as a string
            $question = 'I have a table in mysql. The table is called ' . $loadedModel->getTable() . '. The table contains columns: ' . $fillableAttributes . '. Write the mysql query to pluck the count of ' . $request->search;
            $aiResponse = $this->askQuestion($question);
            $sqlQuestion = trim($aiResponse);
            $sqlQuestion = str_replace(array("\r", "\n", ';', '.', '<code>', '</code>'), '', $sqlQuestion);
            $sqlQuestion = $this->extractSelectString(str_replace(array(';'), '', $sqlQuestion)); */
            // $sqlQuestion = "SELECT COUNT(*) FROM example_data WHERE source = 'Facebook'";
            $sqlQuestion = "SELECT COUNT(*) FROM example_data";
        }

        try {
            $results = DB::select($sqlQuestion);
            $results = reset($results[0]);
        } catch (\Throwable $th) {
            $results = array();
        }

        $data = [
            'model' => $request->model,
            'count' => rand(1, 500),

            // 'count' => $results,
            'query' => $sqlQuestion,
        ];
        return $data;
    }
    function extractSelectString($string)
    {
        $pattern = '/SELECT.*/';
        preg_match($pattern, $string, $matches);
        return $matches[0];
    }

    public function askQuestion($question)
    {
        $open_ai = new OpenAi('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');
        $complete = $open_ai->completion([
            'model' => 'text-davinci-003',
            'prompt' => $question,
            'temperature' => 0.9,
            'max_tokens' => 3000,
            'frequency_penalty' => 0,
            'presence_penalty' => 0.6,
        ]);

        $data = json_decode($complete);

        if (!$data)
            dd('no data from chatgpt');
        $text = $data->choices[0]->text;
        return $text;
    }


    /**
     * Display the Telescope view.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $sqlQuestion = $request->sql;
        $sqlQuestion = str_replace('COUNT(*)', '*', $sqlQuestion);


        try {
            $results = DB::select($sqlQuestion);
        } catch (\Throwable $th) {
            $results = array();
        }

        $cols = [];
        foreach ($results[0] as $key => $name) {
            $cols[] = $key;
        }

        $data = [
            'results' => $results,
            'columns' => $cols
        ];

        return $data;
    }
}
