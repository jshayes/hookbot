<?php

namespace HookBot\Logging;

use Carbon\Carbon;
use Monolog\Logger as MonoLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Logger
{
	private static $instance;
	private $logger;

	private function __construct()
	{
		$fileName = sprintf('logs/hookbot-%s.log', Carbon::now()->format('Ymd'));
		$lineFormatter = new LineFormatter(null, null, true, true);
		$streamHandler = new StreamHandler(storage_path($fileName), MonoLogger::DEBUG);
		$streamHandler->setFormatter($lineFormatter);
		$this->logger = new MonoLogger('HookBot', [$streamHandler]);
	}

	public function __call($name, $arguments)
	{
		call_user_func_array([$this->logger, $name], $arguments);
	}

	public static function __callStatic($name, $arguments)
	{
		$logger = Logger::getInstance();
		call_user_func_array([$logger, $name], $arguments);
	}

	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new Logger();
		}

		return self::$instance;
	}
}
