<?php

/**
 * Application Component Express
 *
 */

namespace vuongminh\ac;

use Yii;

/**
 * @author Vương Xương Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 * 
 * @property \yii\web\Request $request
 * @property \yii\web\Response $response
 * @property \yii\web\Session $session
 * @property \yii\web\User $user
 * @property \yii\rbac\ManagerInterface $auth
 * @property array $requestPost
 * @property array $requestGet
 *
 */
trait AppComponent {

    /**
     *
     * @var \yii\web\Request; 
     */
    public function getRequest() {
        return Yii::$app->getRequest();
    }

    /**
     *
     * @var array
     */
    public function getRequestGet() {
        return $this->request->get();
    }

    /**
     *
     * @var array
     */
    public function getRequestPost() {
        return $this->request->post();
    }

    /**
     *
     * @var \yii\web\Response
     */
    public function getResponse() {
        return Yii::$app->getResponse();
    }

    /**
     *
     * @var \yii\web\Session
     */
    public function getSession() {
        return Yii::$app->getSession();
    }

    /**
     *
     * @var \yii\web\User; 
     */
    public function getUser() {
        return Yii::$app->getUser();
    }

    /**
     *
     * @var \yii\rbac\ManagerInterface; 
     */
    public function getAuthManager() {
        return Yii::$app->getAuthManager();
    }

}
