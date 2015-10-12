<?php

namespace SoapBot\GitHooks;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Dispatcher
{
	public function dispatch(Request $request, $hookType)
	{
		$class = sprintf('%s\\Hooks\\%sHookHandler', __NAMESPACE__, Str::studly($hookType));

		if (!class_exists($class)) {
			return ['error' => 'No hook handler defined.'];
		}

		$handler = new $class();
		return $handler->handle($request);
	}
}
