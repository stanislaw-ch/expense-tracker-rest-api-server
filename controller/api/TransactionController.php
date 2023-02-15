<?php

class TransactionController extends BaseController
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
                $transactionModel = new TransactionModel();
                $intLimit = 20;
                if (isset($queryStringParams) && $queryStringParams) {
                    $intLimit = $queryStringParams;
                }
                $params = array($userId, $userId, $userId, $intLimit);
                $transactions = $transactionModel->getAll($params);
                $responseData = json_encode($transactions);
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