<?php


namespace App\ViewModel;


interface SelectPostViewModel
{
    public function load($posts);
    public function display();
}
