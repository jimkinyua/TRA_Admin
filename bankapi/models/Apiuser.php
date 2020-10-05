<?php

use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;

namespace app\models;


use Yii;
 
class Apiuser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface 
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'APIUsers';
    }
 
    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
 
    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->APIUserID;
    }
 
    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
 
    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
 
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
 
}