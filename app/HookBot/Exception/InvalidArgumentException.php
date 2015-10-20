<?php

namespace HookBot\Exception;

class InvalidArgumentException extends Exception
{
	protected $errorType = 'invalid_argument';
	protected $errorCode = 400;
}
