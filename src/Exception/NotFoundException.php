<?php

namespace Trejjam\MailChimp\Exception;

use Exception;

class NotFoundException extends \LogicException
{
	public function __construct($message = "", $code = 0, Exception $previous = NULL)
	{
		if ($code instanceof Exception && is_null($previous)) {
			$previous = $code;
			$code = 0;
		}

		parent::__construct($message, $code, $previous);
	}
}
