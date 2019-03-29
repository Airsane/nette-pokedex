<?php
/**
 * Created by PhpStorm.
 * User: patri
 * Date: 27.03.2019
 * Time: 10:04
 */

namespace App\Presenters;

use Nette;
use App\Model\PokemonManager;
use App\Model\TrainerManager;


class ProfilePresenter extends Nette\Application\UI\Presenter
{
    private $pokemonManager;
    private $trainerManager;

    public function __construct(PokemonManager $pokemonManager, TrainerManager $trainerManager)
    {
        $this->pokemonManager = $pokemonManager;
        $this->trainerManager = $trainerManager;
    }

    public function renderShow($id)
    {
        if (!isset($id) || empty($id))
            $id = $this->user->getIdentity()->id;
        $trainer = $this->trainerManager->getUser($id);
        if (!$trainer) {
            $this->error('Page not found');
        }
        $this->template->trainer = $trainer;
        $this->template->pokemonsCount = $this->pokemonManager->getUserPokemonsCount($id);
        $this->template->pokemons = $this->pokemonManager->getUserPokemons($id,4,0,'RAND()');

    }

}