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
$app->post('target', 'Controller@processGithubHook');
$app->get('ping', function() {
	return 'pong';
});
$app->post('slack/more', 'Controller@slack');
