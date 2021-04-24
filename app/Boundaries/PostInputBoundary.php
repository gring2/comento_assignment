<?php


namespace App\Boundaries;


use App\Models\User;

interface PostInputBoundary
{
    public function get(string $name, $default = null);

    public function getUser(): ?User;
}
