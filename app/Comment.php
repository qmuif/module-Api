<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * Разрешенные к заполнению поля
     */
    protected $fillable = ['postId', 'author', 'comment'];

    /**
     * Получение коментариев
     * @param $postId
     * @return mixed, массив комментариев к посту
     */
    static public function getComment($postId) {
        $comments = Comment::where('postId',$postId)->select('id', 'author', 'comment', 'created_at')->get();
        return $comments;
    }
}
