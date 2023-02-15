<?php

class CategoryController extends BaseController
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
                $categoryModel = new CategoryModel();
                $params = array($userId);
                $categories = $categoryModel->getAll($params);
                $responseData = json_encode($categories);
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