<?php

namespace Khill\Lavacharts\Exceptions;

class DataTableNotFound extends \Exception
{
    public function __construct()
    {
        $message = '\Khill\Lavacharts\Configs\DataTable could not be found, was Lavacharts loaded?';

        parent::__construct($message);
    }
}
