<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Comment;
use Validator;

class PostsController extends Controller
{
    /**
     * Просмотр всех постов
     *
     */
    public function index(){
        $posts = Post::getAll();
        return response()->json($posts, 200)->setStatusCode(200,'List posts');
    }

    /**
     * Просмотр одной записи
     *
     */
    public function show(Post $post){
        //запрос кометариев поста
        $comments = Comment::getComment($post->id);
        $post = Post::getPost($post);
        return response()->json(['post' => $post,
            'comment' => $comments], 200)->setStatusCode(200,'View post');
    }

    /**
     * Создание поста
    */
    public function store(Request $request){
        //проверка правильности введенных данных
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:posts|max:255',
            'anons' => 'required|string',
            'text' => 'required',
            'image' => 'required|max:2048|mimes:jpg,png',
        ]);
        //если есть ошибки вернуть их в json формате
        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>$validator->messages()
            ], 400)->setStatusCode(400,'Creating error');
        }
        /**
         * Перемещение файла
         */
        $file = $request->file('image');
        $file->move('../post_images', time().'_'.$file->getClientOriginalName());
        $newRequest = $request->all();
        $newRequest['image'] = '/post_images'.time().'_'.$file->getClientOriginalName();
        //-----------------------------------------------------------------------------------
        $post = Post::create($newRequest);
        return response()->json([
            'status'=>true,
            'post_id'=>$post->id
        ], 201)->setStatusCode(201,'Successful creation');
    }

    /**
     * Редактирование поста
     */
    public function update(Request $request, Post $post){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:posts|max:255',
            'anons' => 'required|string',
            'text' => 'required',
            'image' => 'required|max:2048|mimes:jpg,png',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>$validator->messages()
            ], 400)->setStatusCode(400,'Editing error');
        }
        /**
         * Перемещение файла
         */
        $file = $request->file('image');
        $file->move('../post_images', time().'_'.$file->getClientOriginalName());
        $newRequest = $request->all();
        $newRequest['image'] = '/post_images'.time().'_'.$file->getClientOriginalName();
        //-----------------------------------------------------------------------------------
        $post->update($newRequest);
        $post = Post::getPost($post);
        return response()->json([
            'status' => true,
            'post' => $post
        ], 201)->setStatusCode(201,'Successful creation');
    }

    /**
     * Удаление поста
     */
    public function delete(Post $post){
        $post->delete();
        return response()->json([
            'status'=>true
        ],201)->setStatusCode(201,'Successful delete');
    }

    /**
     * Поиск записей по тегу
     */
    public function search($tags){
        $post = Post::where('tags','like',('%'.$tags.'%'))->get();
        return response()->json($post, 201);
    }
}
