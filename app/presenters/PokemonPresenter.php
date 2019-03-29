<?

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class PokemonPresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderShow($id)
    {
        $pokemon = $this->database->table('pokemons')->select('*')->select(':pokemon_img.*')->get($id);
        if (!$pokemon) {
            $this->error('Page not found');
        }
        $this->template->pokemon = $pokemon;
        $this->template->types = $this->database->table('types')->where(':pokemon_has_type.pokemon_id', $pokemon->id);
        $evolution = $this->database->table('evolutionchain')->where('id_evolution', $this->template->pokemon->id_evolution);
        $evolutionArr = array();
        for ($i = 0; $i < count($evolution); $i++) {
            if ($i == 0) {
                $evolutionArr['parent'] = $evolution[$i]->id_from;
            }
            if (!isset($evolutionArr[$evolution[$i]->id_from]))
                $evolutionArr[$evolution[$i]->id_from] = [];
            array_push($evolutionArr[$evolution[$i]->id_from], $evolution[$i]->id_to);
        }
        $this->template->evolution = $evolutionArr;
    }

    public function getPokemon($id)
    {
        return $this->database->table('pokemons')->select('name')->select('id')->select(':pokemon_img.*')->get($id);
    }

    public function handleTest($karel)
    {
        return $karel;
    }

}