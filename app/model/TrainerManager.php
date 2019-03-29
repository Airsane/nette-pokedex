<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 28.03.2019
 * Time: 17:51
 */

namespace App\Model;

use Nette;

class TrainerManager
{
    use Nette\SmartObject;

    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function getUser($id)
    {
        return $this->database->query('SELECT users.*,teams.name FROM users JOIN teams on users.team_id = teams.id WHERE users.id = ?',$id)->fetch();
    }



}