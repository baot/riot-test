<?php 
namespace Server\Models;

class Summoner
{
    /*
     * Summoner id
     *
     * @var int
     */
    public $id;

    /*
     * Summoner name
     *
     * @var string
     */
    public $name;

    /*
     * Summoner recent match records(win/loss), at most 7 matches 
     *
     * @var array
     */
    public $record;

    /*
     * constructor
     *
     * @param int $id
     * @param string $name
     * @param array $records
     */
    public function __construct($id, $name, $record)
    {
        $this->id = $id;
        $this->name = $name;
        $this->record = $record;
    }
}
