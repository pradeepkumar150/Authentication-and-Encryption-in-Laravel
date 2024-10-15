<?php

namespace App\Http\Controllers;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;

class ArticleController extends Controller
{
    public function storeArticle(Request $request)
    {
        
        $rules = [
            'title' => 'required',
            'content' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);
        if($validate->fails()){
            return response()->json(['status' => false, 'errors' => $validate->errors()->first()], 400);
        }else{
            $id = Auth::user()->id;

            $user = new Article();
            $user->userId   = $id;
            $user->title =   $request->title;
            $user->content = $request->content;
            $user->save();
            
            if($user){
                return response()->json([
                    "status" => true,
                    "message" => 'Created successfully',
                    "data" => [],
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => 'Error',
                    "data" => [],
                ]);
            } 
        }     
        
    }

    public function listArticle(){
        $uid = Auth::user()->id;
        $articles = Article::where('userId', $uid)->get();
        if (!$articles) {
            return response()->json(['status' => false, 'message' => 'Article not found'], 404);
        }else{
            return response()->json(['status' => true, 'articles' => $articles], 200);
        }
    }

    public function articleById($id)
    {
        $uid = Auth::user()->id; 
        $article = Article::find($id);
        
        if (!$article) {
            return response()->json(['status' => false, 'message' => 'Article not found'], 404);
        }elseif($article->userId !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }else{
            return response()->json(['status' => true, 'articles' => $article], 200);
        }
    }
    
    public function updateArticle(Request $request, $id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['status' => false, 'message' => 'Article not found'], 404);
        }

        if ($article->userId !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        //return $request->all();
        $rules = [
            'title' => 'required',
            'content' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }else{
            
            $uid = Auth::user()->id;

            $article->userId   = $uid;
            $article->title =   $request->title;
            $article->content = $request->content;
            //$article->update();
            
            if($article->save()){
                return response()->json([
                    "status" => true,
                    "message" => 'Updated successfully',
                    "data" => [],
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => 'Error',
                    "data" => [],
                ]);
            } 
        }     
        
    }

    public function deleteArticle($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['status' => false, 'message' => 'Article not found'], 404);
        }
    
        if($article->delete()){
    
            return response()->json(['status' => true, 'message' => 'Article deleted'], 200);
        }else{
            return response()->json(['status' => false, 'message' => 'Article not Delete'], 401);
        }
    }

    
}
