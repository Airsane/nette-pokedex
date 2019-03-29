<?php

namespace App\Presenters;

use Nette;
use App\Model\PokemonManager;


final class PokedexPresenter extends Nette\Application\UI\Presenter
{

    private $pokemonManager;

    public function __construct(PokemonManager $pokemonManager)
    {
        $this->pokemonManager = $pokemonManager;

    }

    public function renderDefault($id, $page = 1)
    {
        if (!isset($id) || empty($id))
            $id = $this->user->getIdentity()->id;
        $pokemonCount = $this->pokemonManager->getUserPokemonsCount($id);
        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($pokemonCount);
        $paginator->setItemsPerPage(40);
        $paginator->setPage($page);
        $pokemons = $this->pokemonManager->getUserPokemons($id, $paginator->getLength(), $paginator->getOffset());

        $this->template->pokemons = $pokemons;
        $this->template->paginator = $paginator;
    }

}
