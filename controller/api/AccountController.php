<?php

class AccountController extends BaseController
{
    public function listAction()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $responseData = '';

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $query = $this->getQueryStringParams();
        $userId = $query['userId'] ?? null;

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $accountModel = new AccountModel();
                $params = array($userId);
                $accounts = $accountModel->getAll($params);
                $responseData = json_encode($accounts);
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        $this->httpOutput($strErrorDesc, $strErrorHeader, $responseData);
    }
}