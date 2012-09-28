<?php

return function($next) {
    try {
        $next();
    } catch (Exception $e) {
        // todo: add logging

        /* @var $response Strapi\Response */
        $response = $this->load('response');
        $response->reset(500, 'An unknown error has occurred');
        if ($e instanceof Strapi\HttpErrorException) {
            $response->reset($e->getCode(), $e->getMessage(), ['Content-Type' => 'text/plain']);
        }

        if (!empty($this->load('config')['render_exceptions'])) {
            $response->body(implode("\n", [
                $response->body(),
                '------------------------------------------------------------',
                'Message: ' . $e->getMessage(),
                'File: ' . $e->getFile(),
                'Line: ' . $e->getLine(),
                'Code: ' . $e->getCode(),
                "Trace: \n" . $e->getTraceAsString()
            ]));
        }

        call_user_func($this->load('middleware/responder'), function(){});
    }
};
