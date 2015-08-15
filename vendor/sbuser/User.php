<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 07.08.15
 * Time: 12:15
 */

namespace app\sbuser;


use app\sitebuilder\Model;

class User extends Model
{
    public $id;
    public $login;
    public $password;
    public $email;

    public $fields = [
        'id' => [
            'type' => 'INT'
        ],

        'login' => [
            'type' => 'VARCHAR',
            'length' => 50,
            'required' => true,
            'label' => 'Логін'
        ],

        'password' => [
            'type' => 'VARCHAR',
            'length' => 50,
            'required' => true,
            'label' => 'Пароль'
        ],

        'email' => [
            'type' => 'email',
            'required' => true,
            'length' => 50
        ]
    ];

    function tableName()
    {
        return 'users';
    }

    function __construct($params = null)
    {
        session_start();
        parent::__construct($params);
    }

    function isAuth()
    {
        if (empty($_SESSION['user_id']) or empty($_SESSION['user_password']))
            return false;

        $user = self::getByPK($_SESSION['user_id']);

        if (empty($user))
            return false;

        if ($user->password == $_SESSION['user_password'])
            return true;
        
        return false;
    }

    function sign_in() {
        session_destroy();
        session_start();
        if (empty($this->login) or empty($this->password))
            return;

        $this->password = md5($this->password);

        $user = self::where(['login' => $this->login, 'password' => $this->password]);

        var_dump($user);

        if (count($user) == 0)
            return;

        $user = $user[0];

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_password'] = $user->password;
    }
}