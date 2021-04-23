<?php


namespace App\Boundaries;


use App\Models\User;

class GetPostBoundary implements PostInputBoundary
{
    private $user;
    private $id;
    public function __construct($user, $id)
    {
        $this->user = $user;
        $this->id = $id;
    }


    public function get(string $name = null, $default = null)
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}
