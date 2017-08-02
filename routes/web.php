<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->group(['prefix' => 'api/v1'], function($app){
	// Root
	$app->get('/', function () use ($app) {
	    return response()->json(['message' => 'Nothing to see here'], 403);
	});

	$app->group(['prefix' => 'recipe'], function () use ($app) {
		// Get by Id
		$app->get('{id}', 'RecipeController@getRecipeById');
		// Update by Id
		$app->put('{id}', 'RecipeController@updateRecipeById');
		// Add new
		$app->post('/', 'RecipeController@addNewRecipe');
		// Search by cuisine
		$app->get('cuisine/{filter}', 'RecipeController@getRecipeByCuisine');
	});
});



