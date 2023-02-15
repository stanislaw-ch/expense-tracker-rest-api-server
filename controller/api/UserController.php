<?php

class UserController extends BaseController
{
    private $data;
    private array $session;
    private mixed $requestMethod;
    private string $responseData;
    private string $strErrorDesc;
    private string $strErrorHeader;

    public function __construct(){
        session_start();
        $this->data = $this->secureData(array_merge($_POST, $_GET));
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        $this->session = $_SESSION ?? null;
        $this->responseData = '';
        $this->strErrorDesc = '';
        $this->strErrorHeader = '';
    }

    private function getSanitized($data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    private function secureData($data){
        foreach($data as $key => $value){
            if (is_array($value)) $this->secureData($value);
            else {
                $data[$key] = $this->getSanitized($value);
            };
        }
        return $data;
    }

//    public function isAdmin($login = false, $password = false): bool
//    {
//        if (!$login) $login = $_SESSION["login"] ?? false;
//        if (!$password) $password = $_SESSION["password"] ?? false;
//        return mb_strtolower($login) === mb_strtolower($this->config->adm_login) && $password === ($this->config->adm_password);
//    }

    private function hashSecret($str): string
    {
        return md5($str);
    }

    public function login()
    {
        if (strtoupper($this->requestMethod) == 'POST') {
            try {
                $userModel = new UserModel();
                $username = $this->data["login"];
                $password = $this->data["password"];
                $password = $this->hashSecret($password);
                $user = $userModel->getAll(Array($username, $password));
                if (count($user) === 1) {
                    if (isset($username)) {
                        $_SESSION['userId'] = $user[0]['id'];
                        $_SESSION['username'] = $user[0]['username'];
                    }
                    $this->responseData = json_encode($user[0]);
                }

            } catch (Exception $e) {
                $this->strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $this->strErrorDesc = 'Method not supported';
            $this->strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        $this->httpOutput($this->strErrorDesc, $this->strErrorHeader, $this->responseData);
    }

    public function auth() {
        if (strtoupper($this->requestMethod) == 'GET') {
            try {
                if (isset($this->session) && count($this->session) !== 0) {
                    $this->responseData = json_encode($this->session);
                }
            } catch (Exception $e) {
                $this->strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $this->strErrorDesc = 'Method not supported';
            $this->strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        $this->httpOutput($this->strErrorDesc, $this->strErrorHeader, $this->responseData);
    }

    public function logout() {
        if (strtoupper($this->requestMethod) == 'GET') {
            unset($this->session);
            session_destroy();
            exit();
        }
    }
}