<?php 
namespace Config;

use Config\Request as Request;
use Helper\SessionHelper as SessionHelper;
class Router
{
    public static function Route(Request $request)
    {
        $controllerName = $request->getcontroller() . 'Controller';
        $methodName = $request->getmethod();
        $methodParameters = $request->getparameters();          

        $controllerClassName = "Controllers\\". $controllerName;            

        // Verificar si la clase del controlador existe
        if (class_exists($controllerClassName)) {
            $controller = new $controllerClassName;

            // Verificar si el método existe en el controlador
            if (method_exists($controller, $methodName)) {
                if (!isset($methodParameters) || empty($methodParameters)) {
                    call_user_func(array($controller, $methodName));
                } else {
                    call_user_func_array(array($controller, $methodName), $methodParameters);
                }
            } else {
                // Redirigir al login si el método no existe
                SessionHelper::redirectTo404();
            }
        } else {
            // Redirigir al login si el controlador no existe
            SessionHelper::redirectTo404();
        }
    }

}
