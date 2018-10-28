<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //разрешение заполнять тольк указанные поля
    protected $fillable = ['title', 'anons', 'text', 'tags','image'];

    /**
     * Все посты
     * @return Post[]|\Illuminate\Database\Eloquent\Collection
     */
    static public function getAll(){
        $posts = Post::all('title','created_at', 'anons', 'text', 'tags', 'image');
        //разбиение тегов на массив, для отправки
        foreach ($posts as $post){
            $tags = explode(",",$post->tags);
            $post->tags = $tags;
        }
        return $posts;
    }

    /**
     * Вывод поста по заданному в задании условию
     * @param Post $post
     * @return Post|array
     */
    static public function getPost(Post $post){
        $post = [
            'title'=>$post->title,
            'created_at'=>$post->created_at,
            'anons'=>$post->anons,
            'text'=>$post->text,
            'tags'=>explode(",",$post->tags),
            'image'=>$post->image];
        return $post;
    }
}
