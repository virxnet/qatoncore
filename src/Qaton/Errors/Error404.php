<?php

namespace VirX\Qaton\Errors;

use VirX\Qaton\HttpHeaders;
use VirX\Qaton\Error;

class Error404
{
    public function __construct()
    {
        HttpHeaders::setByCode('404');
        new Error(
            'No application fallback controller was found. Has a Qaton application been installed and configured?',
            null,
            1000
        );
    }
}

// TODO: this is a workaround for a bug (this class is always not found by VirX\Qaton\Loader),
//       find out why and remove this after fixing the issue
new Error404();
