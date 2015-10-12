<?php

namespace SoapBot\GitHooks\Hooks;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PullRequestHookHandler
{
	public function handle(Request $request)
	{
		$action = $request->get('action');
		$class = sprintf('SoapBot\\GitHooks\\Events\\PullRequest\\%sEventHandler', Str::studly($action));

		if (!class_exists($class)) {
			return ['error' => 'No event handler exists'];
		}

		$handler = new $class();
		return $handler->handle($request);
	}
}
