<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 27.03.2019
 * Time: 9:01
 */
namespace App\Model;

use Nette;
use Nette\Security as NS;


class MyAuthenticator implements NS\IAuthenticator
{
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        $row = $this->database->table('users')
            ->where('username', $username)->fetch();

        if (!$row) {
            throw new Nette\Security\AuthenticationException('User not found.');
        }

        if (!Nette\Security\Passwords::verify($password, $row->password)) {
            throw new Nette\Security\AuthenticationException('Invalid password.');
        }

        return new Nette\Security\Identity($row->id, $row->role_id, ['username' => $row->username]);
    }
}