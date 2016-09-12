<?php

/**
 * Active Action
 *
 */

namespace vuongminh\ac;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;

/**
 * @author Vương Xương Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 * 
 * @property ActiveController $controller
 */
class ActiveAction extends Action {

    use AppComponent;

    /**
     *
     * @var string suffix of model class
     */
    public $modelPrefix = "";

    /**
     * A model of action
     * @var mixed [[\yii\base\Model]], or [[\yii\db\ActiveRecord]];
     */
    public $model;

    /**
     * A response format of action if not set, response format will be inherit
     * @var string
     */
    public $reponseFormat;

    /**
     * A layout of action if not set, layout will be use of owner controller
     * @var string
     */
    public $layout;

    /**
     * 
     * @throws InvalidConfigException
     */
    public function init() {
        $this->initModel();
        if (!empty($this->layout)) {
            $this->controller->layout = $this->layout;
        }
        if (!empty($this->reponseFormat)) {
            $this->response->format = $this->reponseFormat;
        }
    }

    /**
     * void method init model
     */
    private function initModel() {
        $model = $this->model;
        if (!empty($model) && (is_string($model) || is_array($model))) {
            $this->model = Yii::createObject($model);
        } elseif (empty($model)) {
            $modelNamespace = $this->getModelNamespace();
            $modelClassName = $this->modelPrefix . str_replace(" ", "", ucwords(implode(" ", explode("-", $this->id))));
            $modelClass = $modelNamespace . "\\" . $modelClassName;
            if (class_exists($modelClass)) {
                $this->model = Yii::createObject($modelClass);
            }
        }
    }

    /**
     * @return string path store model of action in models directory.
     */
    private function getPathModel() {
        return str_replace("-", "", $this->controller->id);
    }

    /**
     * Support find model namespace
     * @return string
     */
    private function getModelNamespace() {
        $controllerNamespace = Yii::$app->controllerNamespace;
        $explode = explode("\\", $controllerNamespace);
        unset($explode[count($explode) - 1]);
        $baseNamespace = implode("\\", $explode);
        return $baseNamespace . "\\" . "models" . "\\" . $this->getPathModel();
    }

}
