<?php

class NotFoundPageController extends BaseController
{
    public function notFound()
    {
        $responseData = '';
        $strErrorDesc = 'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';

        $this->httpOutput($strErrorDesc, $strErrorHeader, $responseData);
    }
}