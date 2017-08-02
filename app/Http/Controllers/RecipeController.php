<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecipeController extends Controller
{

    private $data;
    private $keys;
    private $errors;
    private $fieldExceptions;

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        // set authorisation middleware
        $this->middleware('auth');
        // make data available
        $this->data = $this->getDataFromCSV();
        // basic init 
        $this->errors = array();
        $this->fieldExceptions = array(
            'id',
            'created_at',
            'updated_at'
        );
    }

    /**
     * Get by id
     * @param  Request $request 
     * @param  Integer  $id      
     * @return Response    
     */
    public function getRecipeById($id) 
    {
        // get recipe
        $getRecipe = $this->getById($id);
        // return record or empty set
        $data = array('data' => (!$getRecipe) ? [] : $getRecipe);
        return response()->json($data);
    }

    /**
     * Find all from a given cuisine
     * @param  Request $request 
     * @param  string  $filter  
     * @return Response
     */
    public function getRecipeByCuisine(Request $request, $filter) //, $start, $end, $count)
    {
        // init
        $recipeData = $this->data;
        $responseArray = array();

        // iterate through data find matches
        foreach ($recipeData as $recipe) {
            if ($recipe['recipe_cuisine'] == $filter) {
                $responseArray[] = $recipe;
            } 
        }

        // setup pagination links
        $pagination = (count($responseArray) === 0) ? array() : array(
            'self' => '',
            'first' => '',
            'prev' => '',
            'next' => '',
            'last' => ''
        );

        // return record or empty set
        $data = array(
            'data' => $responseArray,
            'links' => $pagination 
        );
        return response()->json($data);
    }

    /**
     * Update 
     * @param  Request $request 
     * @param  Int  $id      
     * @return Response           
     */
    public function updateRecipeById(Request $request, $id)
    {
        // get recipe
        $getRecipe = $this->getById($id);
        if (!$getRecipe) {
            response()->json(['message' => 'recipe not found']);
        }

        // get payload
        $payload = $request->json()->all();

        // validate
        if (count($payload) === 0) {

            return response()->json(['errors' => $this->errors], 422);
        }

        // write only valid keys sent in payload
        foreach ($payload as $key => $value) {
            if (in_array($key, $this->keys[0])) {
                $this->data[$id][$key] = filter_var($value, FILTER_SANITIZE_ENCODED);
            }
        };
        
        // update
        if (!$this->setDataToCSV()) {
            return response()->json(['message' => 'recipe not saved']);
        }
        return response()->json(['message' => 'recipe saved']);
    }

    /**
     * Add new recipe
     * @param Request $request
     * @return Response
     */
    public function addNewRecipe(Request $request)
    {
        // get payload
        $payload = $request->json()->all();

        // generate mandatory fields
        $date = date('d/m/Y H:i:s');

        // get the next ID
        $insertId = max(array_keys($this->data)) + 1;

        // generate auto attributes
        $newRecord = array(
            "id" => $insertId,
            "created_at" => $date,
            "updated_at" => $date
        );

        // validate payload if there is enough data to create a new recipe
        $satisfied = true;
        $keys = $this->keys[0];

        foreach ($keys as $validKey) {
            if (array_key_exists($validKey, $payload)) {
                // construct the new record
                $newRecord[$validKey] = filter_var($payload[$validKey], FILTER_SANITIZE_ENCODED);
            } else if (!in_array($validKey, $this->fieldExceptions)){
                // add to error fields
                $this->errors[] = array(
                    "status" => 422,
                    "source" => array("pointer" => $validKey),
                    "title" => "Invalid attribute",
                    "detail" => "$validKey is required"

                );
                $satisfied = false;
            }
        }

        // if not satisfied 
        if (!$satisfied) {
            return response()->json(['errors' => $this->errors], 422);
        }

        // add to array
        $this->data[$insertId] = $newRecord;
    
        // write to CSV 
        if (!$this->setDataToCSV()) {
            return response()->json(['message' => 'recipe not created']);
        }
        return response()->json(['message' => 'recipe created']);
    }

    /**
     * Get by id 
     * @param  Int $id 
     * @return Array     
     */
    private function getById($id)
    {
        $recipeData = $this->data;
        if (!array_key_exists($id, $recipeData)) {
            return false;
        }
        return $recipeData[$id];
    }

    /**
     * Load data from CSV
     * Make CSV keys available
     * 
     * @return Array 
     */
    private function getDataFromCSV()
    {
        try {
            $filename = storage_path('app/recipe-data.csv');

            $keys = array();
            $header = null;
            $dataCSV = array();
            if (($file = fopen($filename, 'r')) !== false)
            {
                while (($row = fgetcsv($file, 1000, ',')) !== false)
                {
                    if(!$header) {
                        $header = $row;
                        $keys[] = $header;
                    }else{
                        $dataCSV[$row[0]] = array_combine($header, $row);
                    }
                }
                fclose($file);
            }
            $this->keys = $keys;
            return $dataCSV;
        }
        catch (Exception $e) {
            return false;
        }
    }

    /**
     * Write back to CSV
     * @return true|false
     */
    public function setDataToCSV()
    {
        try {
            $filename = storage_path('app/recipe-data-new.csv');
            $file_input = fopen($filename,"w");

            fputcsv($file_input, $this->keys[0]);
            foreach ($this->data as $line) {
                fputcsv($file_input, array_values($line)); 
            }
            fclose($file_input);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }
}
