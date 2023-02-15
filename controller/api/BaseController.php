<?php

use JetBrains\PhpStorm\NoReturn;

class BaseController
{
    /**
     * __call magic method.
     */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }
    /**
     * Get URI elements.
     *
     * @return array
     */
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return explode( '/', $uri );
    }
    /**
     * Get querystring params.
     *
     * @return array|null
     */
    protected function getQueryStringParams(): ?array
    {
        parse_str($_SERVER['QUERY_STRING'] ?? null, $query);
        return $query;
    }

    /**
     * Send API output.
     *
     * @param mixed $data
     * @param array $httpHeaders
     */
    #[NoReturn] protected function sendOutput(mixed $data, array $httpHeaders=array()): void
    {
        header_remove('Set-Cookie');
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
        echo $data;
        exit;
    }

    /**
     * @param $strErrorDesc
     * @param $strErrorHeader
     * @param $responseData
     * @return void
     */
    #[NoReturn] protected function httpOutput($strErrorDesc, $strErrorHeader, $responseData): void
    {
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}