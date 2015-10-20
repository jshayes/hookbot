<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use HookBot\GitHooks\Dispatcher;
use HookBot\Logging\Logger;

class Controller extends BaseController
{
    public function processGithubHook(Request $request, LoggerInterface $logger)
    {
    	$hookType = $request->header('X-GitHub-Event');
    	$dispatcher = new Dispatcher();
    	$response = $dispatcher->dispatch($request, $hookType);
    	return response()->json($response);
    }

    public function slack(Request $request) {
    	Logger::info($request->all());
    }
}
