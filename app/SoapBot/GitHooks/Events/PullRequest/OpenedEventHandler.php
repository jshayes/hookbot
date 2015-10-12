<?php

namespace SoapBot\GitHooks\Events\PullRequest;

use Illuminate\Http\Request;

class OpenedEventHandler
{
	public function handle(Request $request)
	{
		return [
			'hook_type' => 'PullRequest',
			'event_type' => 'Opened'
		];
	}
}
