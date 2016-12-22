
 [![Total Downloads](https://poser.pugx.org/vuongminh/yii2-action-controller/downloads)](https://packagist.org/packages/vuongminh/yii2-action-controller)
[![License](https://poser.pugx.org/vuongminh/yii2-action-controller/license)](https://packagist.org/packages/vuongminh/yii2-action-controller)

Action controller
============================

It's an extension of `yii\base\Action`. It help you code fast, clean and easy manage actions of controller.

If your project have many actions in one controller and one action have many scenarios, maybe some difficults for managing your complex code later. This extension may help you solve that problem.


Installation
-------------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist vuongminh/yii2-action-controller
```

or add

```json
"vuongminh/yii2-action-controller": "v1-beta"
```

to the require section of your composer.json.

Basic usage
------------

In your controller must extends an abstract class `vuongminh\ac\ActiveController` and define method listAction return an array store name of actions.

```php
<?php
namespace app\controllers;

use vuongminh\ac\ActiveController;

class GuestController extends ActiveController
{

     public function listAction()
     {
         return ['login', 'forgot-password'];
     }   
}
```

After define actions name you create directory have name of controller name in `@app\controllers\` and create action files have prefix "Action" . "name action", you can override prefix property `$actionPrefix` default is Action.

      @app/controllers/guest/ActionLogin.php
      @app/controllers/guest/ActionForgotPassword.php



In action files you define an action class like normal of yii

```php
<?php

namespace app\controllers\guest;

use yii\base\Action;


class ActionLogin extends Action
{
    public function run(){
        return $this->controller->render("login");
    }
}
```

Advanced usage
------------

In this case your action class must be extends an `vuongminh\ac\ActiveAction` and you can customize layout, class, response format, model of actions.

```php
<?php
namespace app\controllers;

use vuongminh\ac\ActiveController;

class GuestController extends ActiveController
{
     public function listAction()
     {
         return [
             'login' => [
                 'class' => 'app\controllers\customguest\Login',
                 'layout' => 'abc',
             ],
             'forgot-password' => [
                 'reponseFormat' => \yii\web\Response::FORMAT_JSON
             ]
         ];
     }   
}
```

Short call app component in action class:

```php
<?php

namespace app\controllers\guest;

use vuongminh\ac\ActiveAction;


class ActionLogin extends ActiveAction
{
    public function run(){
        $user = $this->user; // equivalent to \Yii::$app->user
        $postData = $this->requestPost; // equivalent to \Yii::$app->request->post()
        $request = $this->request; // equivalent to \Yii::$app->request
        $response = $this->response; // equivalent to \Yii::$app->response
        $session = $this->session; // equivalent to \Yii::$app->session
        $auth = $this->authManager; // equivalent to \Yii::$app->authManager
        $getData = $this->requestGet; // equivalent to \Yii::$app->request->get()
    }
}
```

When init ActiveAction will check class exist `@app\models\controllername\actioname` if exist it auto create an object and set to property `$model` of action class.

Example: I have a model class `app\models\guest\Login` Login is a name of my action. In `ActionLogin` it auto define and set to property `$model`

```php
<?php

namespace app\controllers\guest;

use vuongminh\ac\ActiveAction;

/**
 *
 * @property \app\models\guest\Login $model
 */
class ActionLogin extends ActiveAction
{
    public function run(){
        if($this->model->load($this->requestPost) && $this->model->login()){
            return $this->controller->redirect(['user/home']);
        }
        return $this->controller-render("login");
    }
}
```

If you want to config model class, you can config it in controller:

```php
<?php
namespace app\controllers;

use vuongminh\ac\ActiveController;

class GuestController extends ActiveController
{

     public function listAction()
     {
         return [
             'login' => [
                 'layout' => 'front-end',
                 'model' => 'app\models\Login'
             ],
              'forgot-password' => [
                  'reponseFormat' => \yii\web\Response::FORMAT_JSON,
                  'model' => [
                      'class' => 'app\models\Login',
                      'propertyName1' => 'value',
                      'propertyName2' => 'value'
                  ],
              ]            
         ];
     }   
}
```

Scenarios in ActiveRecord
------------

Abstract class `vuongminh\ac\ActiveRecord` is an extension of `yii\db\ActiveRecord` it help you solve problem below:

Your AR model may have many scenarios and maybe one scenario have many sub-scenario and you have many many rules it so difficult control them. My ActiveRecord pattern may help you.

I used GII to generate a model AR User and I change an extends class `\yii\db\ActiveRecord` to an abstract class `\vuongminh\ac\ActiveRecord` and change method rules() to tableRules()

```php
<?php

namespace app\models;

/**
 * This is the model class for table "users".
 *
 * @property string $iId
 * @property string $cEmail
 * @property string $cPassword
 */
class User extends \vuongminh\ac\ActiveRecord  {
    
        public function tableRules() {
            return [
                [['iId'], 'safe'],
                [['cEmail', 'cPassword'], 'string', 'max' => 256],
             ];
        }
}
```

and I have scenario forgot password model extends User and method rules of this scenario change to scenarioRules, it will merge with table rules

```php
<?php

namespace app\models\guest;
use app\models\User;
/**
 * This is the model class for table "users".
 *
 * @property string $iId
 * @property string $cEmail
 * @property string $cPassword
 */
class ForgotPassword extends User  {
    
        public $cRePassword, $cReCaptcha, $cVerifyCode;
        
        public function scenarioRules() {
            return [
                [['cEmail', 'cReCaptcha', 'cVerifyCode', 'cPassword', 'cRePassword'], 'required'],
                 [['cEmail'], 'exist'],
                ['cVerifyCode', 'checkVerifyCode'],
                ['cVertifyCode', 'required'],
                ['cRePassword', 'compare', 'compareAttribute' => 'cPassword']
             ];
        }
        
        public function scenarios(){
            return [
                'sendVerifyCode' => ['cEmail', 'cReCaptcha'],
                'vertifyCode' => ['cVerifyCode', 'cPassword', 'cRePassword']
            ];            
        }
}
```
