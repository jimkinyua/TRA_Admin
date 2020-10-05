<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $UserID
 * @property string $LastName
 * @property string $MiddleName
 * @property string $FirstName
 * @property string $PostalAddress
 * @property string $PostalCode
 * @property string $Town
 * @property integer $CountryID
 * @property string $PhoneNumber
 * @property string $Mobile
 * @property string $Email
 * @property string $Url
 * @property string $UserName
 * @property string $Password
 * @property integer $UserStatusID
 * @property string $CreatedDate
 * @property integer $CreatedUserID
 * @property string $Telephone
 * @property integer $Admin
 * @property string $Salt
 * @property integer $Blocked
 * @property string $CompanyName
 * @property integer $UserTypeID
 * @property string $PIN
 * @property string $Website
 * @property string $PhysicalLocation
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CountryID', 'UserStatusID', 'CreatedUserID', 'Admin', 'Blocked', 'UserTypeID'], 'integer'],
            [['CreatedDate'], 'safe'],
            [['LastName', 'MiddleName', 'FirstName', 'PostalAddress', 'PostalCode', 'Town', 'PhoneNumber', 'Mobile', 'Url', 'UserName'], 'string', 'max' => 50],
            [['Email', 'CompanyName'], 'string', 'max' => 200],
            [['Password', 'Salt'], 'string', 'max' => 128],
            [['Telephone', 'PIN'], 'string', 'max' => 45],
            [['Website', 'PhysicalLocation'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'UserID' => 'User ID',
            'LastName' => 'Last Name',
            'MiddleName' => 'Middle Name',
            'FirstName' => 'First Name',
            'PostalAddress' => 'Postal Address',
            'PostalCode' => 'Postal Code',
            'Town' => 'Town',
            'CountryID' => 'Country ID',
            'PhoneNumber' => 'Phone Number',
            'Mobile' => 'Mobile',
            'Email' => 'Email',
            'Url' => 'Url',
            'UserName' => 'User Name',
            'Password' => 'Password',
            'UserStatusID' => 'User Status ID',
            'CreatedDate' => 'Created Date',
            'CreatedUserID' => 'Created User ID',
            'Telephone' => 'Telephone',
            'Admin' => 'Admin',
            'Salt' => 'Salt',
            'Blocked' => 'Blocked',
            'CompanyName' => 'Company Name',
            'UserTypeID' => 'User Type ID',
            'PIN' => 'Pin',
            'Website' => 'Website',
            'PhysicalLocation' => 'Physical Location',
        ];
    }
}
