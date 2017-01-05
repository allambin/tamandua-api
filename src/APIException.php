<?php

namespace Inextends\Tamandua;

class APIException extends \Exception
{
    /**
     * 
     * @param string|int $code
     * @param string $extraMessage
     */
    public function __construct($code, $extraMessage = "")
    {
        $eCC = new ErrorCodesCollection();
        $message = $eCC->getError($code);
        if(!empty($extraMessage)) {
            $message .= " - " . $extraMessage;
        }
        parent::__construct($message, $code);
    }
}
