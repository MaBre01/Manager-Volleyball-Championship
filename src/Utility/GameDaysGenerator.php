<?php


namespace App\Utility;


use App\Entity\Championship;
use App\Entity\GameDay;

class GameDaysGenerator
{
    private $championship;

    public function __construct(Championship $championship)
    {
        $this->championship = $championship;
    }

    public function generateGameDays(): void
    {
        $numberOfTeams = $this->championship->getTeams()->count();

        if( ($numberOfTeams % 2) === 0 ){
            $numberOfGameDays = $numberOfTeams - 1;
        }
        else{
            $numberOfGameDays = $numberOfTeams;
        }


        for($i = 1; $i <= $numberOfGameDays; $i ++){
            $gameDay = new GameDay($i, false, $this->championship);

            $this->championship->addGameDay( $gameDay );
        }
    }
}