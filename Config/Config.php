<?php
namespace Config;
define("ROOT", dirname(__DIR__) . "/");
define("FRONT_ROOT", "/FinalProject4/");
define("VIEWS_PATH","Views/");
define("UPLOADS_PATH", "Upload/");
define("PETS_PATH", UPLOADS_PATH . "Pets/");
define("PAYMENT_PATH", UPLOADS_PATH . "Payments/");
define("GROUPS_PATH", UPLOADS_PATH . "ImageGroups/");
define("USER_PATH", UPLOADS_PATH . "UserImages/");
define("INCIDENT_PATH", UPLOADS_PATH . "IncidentImages/");
define("CSS_PATH", FRONT_ROOT.VIEWS_PATH . "css/");
define("JS_PATH", FRONT_ROOT.VIEWS_PATH . "js/");
define("IMG_PATH",FRONT_ROOT.VIEWS_PATH . "img/");
define("MODULE_PATH",FRONT_ROOT."vendor/");
define("DB_HOST", "localhost");
define("DB_NAME", "petheros");
define("DB_USER", "root");
define("DB_PASS", "");

?>