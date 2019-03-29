<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 27.03.2019
 * Time: 8:23
 */

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use App\Model\MyAuthenticator;


class SignPresenter extends Nette\Application\UI\Presenter
{
    private $database;
    private $autentificator;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
        $this->autentificator = new MyAuthenticator($database);
    }

    protected function createComponentSignInForm()
    {
        $form = new Form;
        $form->addText('username', 'Username:')
            ->setRequired('Please enter your username');
        $form->addPassword('password', 'Password:')
            ->setRequired('Please enter your password.');
        $form->addSubmit('send', 'Login');
        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;
    }

    public function signInFormSucceeded(Form $form, \stdClass $values)
    {
        try {
            $this->getUser()->setAuthenticator($this->autentificator);
            $this->getUser()->login($values->username, $values->password);
            $this->redirect('Homepage:');
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Invalid password or username.');
        }
    }

    public function actionOut()
    {
        $this->getUser()->logout();
        $this->flashMessage('Sign out succeeded.');
        $this->redirect('Homepage:');
    }

    protected function createComponentRegisterForm()
    {
        $teams = $this->database->table('teams')->select('id')->select('name')->fetchAssoc('id=name');
        $form = new Form;
        $form->addText('username', 'Username:')
            ->setRequired('Please enter your username')->addRule(Form::MIN_LENGTH,'Minimum username length is %d characters',3)->addRule(Form::PATTERN_ICASE, 'Error invalid username', '^(?=.{3,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$');

        $form->addPassword('password', 'Password:')
            ->setRequired('Please enter your password.')->addRule(Form::MIN_LENGTH, 'Minimum password length is %d characters', 5);;

        $form->addPassword('password2', 'Password Verify: *', 20)
            ->addConditionOn($form['password'], Form::VALID)
            ->addRule(Form::FILLED, 'Password Verify')
            ->addRule(Form::EQUAL, 'Password dont match.', $form['password']);
        $form->addSelect('team', 'Team', $teams);
        $form->addSubmit('send', 'Register');
        $form->onSuccess[] = [$this, 'registerFormSucceeded'];
        return $form;
    }

    public function registerFormSucceeded(Form $form, \stdClass $values)
    {
        try {
            $this->database->beginTransaction();
            try {
                $row = $this->database->table('users')->insert(array('username' => $values->username, 'password' => Passwords::hash($values->password), 'team_id' => $values->team));
                $this->database->table('pokedex')->insert(array('user_id' => $row->id));
                $this->database->commit();
                $this->redirect('Homepage:');
            } catch (PDOException $e) {
                $this->database->rollBack();
                $this->flashMessage('Registration failed');
            }
        } catch (Nette\Database\UniqueConstraintViolationException $e) {
            $this->flashMessage('Registration failed. This username is already taken');

        }
    }
}
