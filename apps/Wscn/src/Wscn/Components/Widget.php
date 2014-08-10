<?php

namespace Wscn\Components;

class Widget extends \Eva\EvaEngine\Mvc\User\Component
{
    public function actionHelper($controller, $action, $params = null, $namespace = null)
    {
        return $this->reDispatch(array(
            'namespace' => $namespace,
            'controller' => $controller,
            'action' => $action,
            'params' => $params,
        ));
    }
}
