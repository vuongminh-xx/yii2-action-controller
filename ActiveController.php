<?php

/**
 * Acvite Controller
 *
 */

namespace vuongminh\ac;

use Yii;
use yii\web\Controller;
use yii\base\InvalidConfigException;

/**
 * @author Vương Xương Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
abstract class ActiveController extends Controller {

    /**
     *
     * @var string suffix of controller action class
     */
    public $actionPrefix = "Action";

    public function actions() {
        $listAction = [];
        foreach ($this->listAction() as $action => $actionCfg) {
            if (!(is_string($action) && is_array($actionCfg)) && !(is_int($action) && is_string($actionCfg))) {
                throw new InvalidConfigException("Invalid config element in list action please check!");
            }
            if (!is_array($actionCfg)) {
                $action = $actionCfg;
                $actionCfg = [];
            }
            $listAction[$action] = $this->prepareActionCfg($action, $actionCfg);
        }
        return $listAction;
    }

    /**
     * @return array List action of controller
     */
    abstract public function listAction();

    private function prepareActionCfg($action, $actionCfg) {
        if (!isset($actionCfg["class"])) {
            $actionNamespace = $this->getActionNamespace();
            $actionClassName = $this->actionPrefix . str_replace(" ", "", ucwords(implode(" ", explode("-", $action))));
            $actionClass = $actionNamespace . "\\" . $actionClassName;
            $actionCfg["class"] = $actionClass;
        }
        return $actionCfg;
    }

    /**
     * @return string path store all controller actions in controllers directory.
     */
    private function getPathAction() {
        $controller = Yii::$app->controller->id;
        return str_replace("-", "", $controller);
    }

    /**
     * Support find action namespace
     * @return string
     */
    private function getActionNamespace() {
        $controllerNamespace = Yii::$app->controllerNamespace;
        return $controllerNamespace . "\\" . $this->getPathAction();
    }

}
