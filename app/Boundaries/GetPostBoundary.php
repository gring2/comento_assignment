<?php


namespace App\Boundaries;


use App\Models\User;

class GetPostBoundary implements PostInputBoundary
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }


    public function get(string $name = null, $default = null)
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return null;
    }

}
