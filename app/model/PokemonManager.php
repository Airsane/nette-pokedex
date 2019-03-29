<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 28.03.2019
 * Time: 17:51
 */

namespace App\Model;

use Nette;

class PokemonManager
{
    use Nette\SmartObject;

    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function getPokemons($limit, $offset)
    {
        return $this->database->table('pokemons')->select('id')->select('name')->select(':pokemon_img.large')->limit($limit, $offset)->order('name ASC');
        //   return $this->database->table('pokemons')->select(':pokemon_img.large')->order('name ASC');
    }

    public function getPokemonsCount()
    {
        return $this->database->fetchField('SELECT COUNT(*) FROM pokemons');
    }

    public function getUserPokemons($userId, $limit, $offset, $order = "name ASC")
    {
        $pokemons = (array)$this->database->fetchPairs('SELECT pokemon_id FROM user_have_pokemon WHERE user_id = ?', $userId);
        return $this->database->table('pokemons')->select('name')->select('id')->select(':pokemon_img.*')->where('id', $pokemons)->limit($limit, $offset)->order($order);
    }

    public function getUserPokemonsCount($userId)
    {
        return $this->database->fetchField('SELECT COUNT(*) FROM user_have_pokemon WHERE user_id=?', $userId);
    }


}