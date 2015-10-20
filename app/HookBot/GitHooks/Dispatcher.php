<?php

namespace HookBot\GitHooks;

use HookBot\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Dispatcher
{
	public function dispatch(Request $request, $hookType)
	{
		if (is_null($hookType)) {
			throw new InvalidArgumentException('The hook type is not defined');
		}

		$class = sprintf('%s\\Hooks\\%sHookHandler', __NAMESPACE__, Str::studly($hookType));

		if (!class_exists($class)) {
			throw new InvalidArgumentException(sprintf('No hook handler defined for %s', $hookType));
		}

		$handler = new $class();
		return $handler->handle($request);
	}
}
