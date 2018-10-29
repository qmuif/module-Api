<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Auth;
use Validator;

class CommentController extends Controller
{
    /**
     * Добавление коментария
     */
    public function store($post, Request $request)
    {
        if(!Post::find($post)){
            return response()->json([
                'status'=>false
            ], 404)->setStatusCode(404,'Post not found');
        }
        $request->request->add(['postId' => $post]);
        //Если пользователь авторизован
        if(Auth::guard('api')->user()){
            $request->request->add(['author' => 'admin']);
            $validator = Validator::make($request->all(), [
                'comment' => 'required|max:255',
            ]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'author' => 'required|',
                'comment' => 'required|max:255',
            ]);
        }
        //Если есть ошибки вернуть их
        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>$validator->messages()
            ], 400)->setStatusCode(400,'Creating error');
        }
        Comment::create($request->all());
        return response()->json([
            'status'=>true
        ],201)->setStatusCode(201,'Successful creation');
    }

    /**
     * Удаление коментария
     */
    public function delete($post,$comment){
        if(!Post::find($post)){
            return response()->json([
                'status'=>false
            ], 404)->setStatusCode(404,'Post not found');
        }
        else{
            if(!Comment::find($comment)){
                return response()->json([
                    'status'=>false
                ], 404)->setStatusCode(404,'Comment not found');
            }
        }
        Comment::where([['postId',$post], ['id',$comment]])->delete();
        return response()->json([
            'status'=>true
        ], 201)->setStatusCode(201,'Successful delete');
    }
}
