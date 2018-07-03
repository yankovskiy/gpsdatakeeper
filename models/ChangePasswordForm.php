<?php
namespace app\models;

use yii\base\Model;

/**
 * ChangePasswordForm is the model behind the change password form
 */
class ChangePasswordForm extends Model
{
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'New password',
        ];
    }


    /**
     * Changes password.
     *
     * @return bool if password was changed.
     */
    public function changePassword()
    {
        $user = User::findIdentity(\Yii::$app->user->id);
        $user->setPassword($this->password);

        return $user->save(false);
    }
}
