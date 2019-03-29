<?php

namespace App\Presenters;

use Nette;
use App\Model\PokemonManager;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{

    private $pokemonManager;

    public function __construct(PokemonManager $pokemonManager)
    {
        $this->pokemonManager = $pokemonManager;
    }

    public function renderDefault($page = 1)
    {
        $pokemonCount = $this->pokemonManager->getPokemonsCount();

        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($pokemonCount);
        $paginator->setItemsPerPage(40);
        $paginator->setPage($page);

        $pokemons = $this->pokemonManager->getPokemons($paginator->getLength(),$paginator->getOffset());

        $this->template->pokemons = $pokemons;
        $this->template->paginator = $paginator;
    }

}
