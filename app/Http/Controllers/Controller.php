<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use HookBot\GitHooks\Dispatcher;

class Controller extends BaseController
{
    public function processGithubHook(Request $request, LoggerInterface $logger, $type)
    {
    	$dispatcher = new Dispatcher();
    	$response = $dispatcher->dispatch($request, $type);
    	return response()->json($response);
    }
}
