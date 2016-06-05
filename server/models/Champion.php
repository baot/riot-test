<?php
namespace Server\Models;

class Champion
{
    /*
     * Champion avatar object 
     * 
     * @var stdClass
     */
    public $avatar;

    /*
     * Champion spells
     *
     * @var array
     */
    public $spells;

    public function __construct($avatar, $spells)
    {
        $this->avatar = $avatar;
        $this->spells = $spells;
    }
}
