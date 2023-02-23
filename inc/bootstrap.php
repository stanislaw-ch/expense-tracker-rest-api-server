<?php
const PROJECT_ROOT_PATH = __DIR__ . "/../";

require_once PROJECT_ROOT_PATH . "/inc/config.php";
require_once PROJECT_ROOT_PATH . "/controller/Api/BaseController.php";
require_once PROJECT_ROOT_PATH . "/controller/api/TransactionController.php";
require_once PROJECT_ROOT_PATH . "/controller/api/AccountController.php";
require_once PROJECT_ROOT_PATH . "/controller/api/CategoryController.php";
require_once PROJECT_ROOT_PATH . "/controller/api/UserController.php";
require_once PROJECT_ROOT_PATH . "/controller/api/ChartController.php";
require_once PROJECT_ROOT_PATH . "/controller/api/NotFoundPageController.php";
require_once PROJECT_ROOT_PATH . "/model/TransactionModel.php";
require_once PROJECT_ROOT_PATH . "/model/AccountModel.php";
require_once PROJECT_ROOT_PATH . "/model/CategoryModel.php";
require_once PROJECT_ROOT_PATH . "/model/UserModel.php";
require_once PROJECT_ROOT_PATH . "/router/Router.php";