<?php

/**
 * Acvite Record
 *
 */

namespace vuongminh\ac;

use yii\db\ActiveRecord as AR;
use yii\helpers\ArrayHelper;

/**
 * @author Vương Xương Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
abstract class ActiveRecord extends AR {

    abstract public function tableRules();

    public function scenarioRules() {
        return [];
    }

    public function rules() {
        return ArrayHelper::merge($this->scenarioRules(), $this->tableRules());
    }

}
