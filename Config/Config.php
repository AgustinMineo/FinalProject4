<?php
namespace Config;
define("ROOT", dirname(__DIR__) . "/");
define("FRONT_ROOT", "/FinalProject4/");
define("VIEWS_PATH", "Views/");
define("UPLOADS_PATH", "Upload/");
define("CSS_PATH", FRONT_ROOT.VIEWS_PATH . "css/");
define("JS_PATH", FRONT_ROOT.VIEWS_PATH . "js/");
define("IMG_PATH",FRONT_ROOT.VIEWS_PATH . "img/");
define("MODULE_PATH",FRONT_ROOT."vendor/");
define("DB_HOST", "localhost");
define("DB_NAME", "petheros");
define("DB_USER", "root");
define("DB_PASS", "");
?>