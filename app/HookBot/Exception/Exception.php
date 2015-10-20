<?php

namespace HookBot\Exception;

use Exception as BaseException;

abstract class Exception extends BaseException
{
	protected $errorType = '';
	protected $errorCode = 500;

	public function getErrorMessage()
	{
		if ($this->errorCode == 500) {
			return 'An unexpected error occurred.';
		}

		return parent::getMessage();
	}

	final public function getErrorCode()
	{
		return $this->errorCode;
	}

	final public function getPayload()
	{
		return [
			'error' => $this->errorType,
			'message' => $this->getErrorMessage()
		];
	}
}
