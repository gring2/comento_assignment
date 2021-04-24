<?php


namespace App\ViewModel;


class DestroyPostJsonViewModel implements DestroyPostViewModel
{
    private $id;
    public function load(string $id)
    {
        $this->id = $id;
    }

    public function display()
    {
        return ['post_id' => $this->id];
    }

}
