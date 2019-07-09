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

    private function parseSections($body) {
		$bodyComponents = preg_split('/#+\s+(\[[A-Za-z\s]+\]).*?\n/', $body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		$sectionLabel = '';
		$sections = [];
		foreach ($bodyComponents as $component) {
			$matches = [];
			if (preg_match('/^\[([A-Za-z\s]+)\]$/', $component, $matches)) {
				$sectionLabel = str_replace(' ', '_', strtolower($matches[1]));
				continue;
			}

			if (!empty($sectionLabel)) {
				$sections[$sectionLabel] = trim($component, " \r\n");
			}
		}

		return $sections;
	}

    public function slack(Request $request) {
    	Logger::info('test', $request->all());
    	$prNumber = $request->get('text');

    	$client = new \GuzzleHttp\Client();
    	$response = $client->request('GET', 'https://api.github.com/repos/SoapBox/soapbox-v4/pulls/' . $prNumber,
    		['query' => [
    			'access_token' => '89d2ccb87582cce241e68d472e569a32f3bcf48b'
    		]]
    	);

    	$pr = json_decode($response->getBody());

    	$client = new \GuzzleHttp\Client();
    	$response = $client->request('GET', 'https://api.github.com/users/' . $pr->user->login,
    		['query' => [
    			'access_token' => '89d2ccb87582cce241e68d472e569a32f3bcf48b'
    		]]
    	);

    	$author = json_decode($response->getBody());

    	$header = sprintf('PR <%s|#%s> by *%s*', $pr->html_url, $pr->number, $author->name);

    	$message = 'There was additional information specified for this pull request.';

    	$sections = $this->parseSections($pr->body);
    	if (array_key_exists('summary', $sections)) {
    		$message = $sections['summary'];
    	}
    	if (array_key_exists('extended', $sections)) {
    		$message .= "\r\n\r\n";
    		$message .= $sections['extended'];
    	}

    	return sprintf("%s\r\n\r\n%s", $header, $message);
    }
}
