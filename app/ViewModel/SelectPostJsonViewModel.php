<?php


namespace App\ViewModel;

class SelectPostJsonViewModel implements SelectPostViewModel
{
    private $posts;

    public function load($posts)
    {
        $this->posts = $posts;
    }

    public function display()
    {
        return [
            'current_page' => $this->posts->currentPage(),
            'last_page' => $this->posts->lastPage(),
            'per_page' => $this->posts->perPage(),
            'total' => $this->posts->total(),
            'data' => $this->mappingData()
        ];
    }

    public function mappingData()
    {
        return $this->posts->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'body' => $item->body,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $item->created_at->format('Y-m-d H:i:s'),

                'author' => [
                    'id' => $item->author->id,
                    'name' => $item->author->name,
                    'email' => $item->author->email,
                ]
            ];
        });
    }
}
