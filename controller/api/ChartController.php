<?php

class ChartController extends BaseController
{
    private TransactionModel $transactionModel;
    private array $transactions;
    private array $params;
    private mixed $year;
    private mixed $month;
    private mixed $toggle;
    private mixed $userId;
    private int $intLimit;
    private array $incomes;
    private const TYPE_EXPENSE = 1;
    private const TYPE_INCOME = 0;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $query = $this->getQueryStringParams();
        $this->year = $query['year'] ?? null;
        $this->month = $query['month'] ?? null;
        $this->toggle = $query['toggle'] ?? null;
        $this->userId = $query['userId'] ?? null;
        $this->intLimit = 20;
        $this->params = [];
    }

    /**
     * @throws Exception
     */
    public function getChartDataByYear($year, $toggle): array
    {
        define("MONTHS", array(
            "January", "February", "March",
            "April", "May", "June",
            "July", "August", "September",
            "October", "November", "December"
        ));

        $arr = [];

        foreach (MONTHS as $item) {
            $month = substr($item, 0, 3);
            $prevYear = $year - 1;
            $arr[] = array(
                'month' => $month,
                'current' =>
                    $this->getExpensesByMonth($year, date("n",strtotime($item)), $toggle)[0][0]
                        ? $this->getExpensesByMonth($year, date("n",strtotime($item)), $toggle)[0][0]
                        : '0.00',
                'previous' =>
                    $this->getExpensesByMonth($prevYear, date("n",strtotime($item)), $toggle)[0][0]
                        ? $this->getExpensesByMonth($prevYear, date("n",strtotime($item)), $toggle)[0][0]
                        : '0.00'
            );

        }

        return $arr;
    }

    /**
     * @throws Exception
     */
    public function getAmount($year, $toggle): array
    {
        $this->toggle = self::TYPE_EXPENSE;
        $this->params = array(
            $this->userId,
            $year,
            $toggle,
            $this->intLimit
        );
        return $this->transactionModel->getAmount($this->params);
    }

    /**
     * @throws Exception
     */
    private function getExpensesByMonth($year, $month, $toggle): array
    {
        $this->toggle = self::TYPE_EXPENSE;
        $this->params = array(
            $this->userId,
            $this->userId,
            $this->userId,
            $year,
            $month,
            $toggle,
            $this->intLimit
        );
        return $this->transactionModel->getSumByMonthAndExpenseType($this->params);
    }

    public function getChartData()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $responseData = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $transactions = $this->getChartDataByYear($this->year, $this->toggle);
                $responseData = json_encode($transactions, JSON_PRETTY_PRINT);
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

    public function getAmountPerYear()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $responseData = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $transactions = $this->getAmount($this->year, $this->toggle);
                $responseData = json_encode($transactions, JSON_PRETTY_PRINT);
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