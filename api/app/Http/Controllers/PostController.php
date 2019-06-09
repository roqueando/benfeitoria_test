<?php

namespace App\Http\Controllers;

use App\Posts;
use Illuminate\Http\Request;

class PostController extends Controller {
    
    /**
     * Create Function
     * Creates a new post with title, text and image.
     */
    public function create(Request $req) {
        try {
            
            $post = new Posts();

            $post->title = $req->title;
            
            /**
             * Check if has a file
             */
            if($req->hasFile('image')) {

                // check if the file is valid, if not, put null
                $file = $req->file('image')->isValid() ? $req->file('image') : null;
                $allowedExtensions = ['png', 'jpg', 'jpeg'];
                
                // check if the extensions of file is allowed in one of those extension in array.
                if(!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
                    return response()->json([
                        'error' => 'Apenas imagens .PNG, .JPG e .JPEG'
                    ], 400);
                }
                
                // create a new name for the file, encrypted to make this unique
                $filename = md5($file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();
                
                $file->move('./', $filename);

                $post->image = $filename;
            }

            $post->text = $req->text;

            if($post->save()) {
                return response()->json([
                    'post' => $post
                ]);
            }

        } catch (\Exception $err) {
            return response()->json([
                'error' => $err->getMessage()
            ], 400);
        }
    }

    public function list() {
        try {
        
            $posts = Posts::orderBy('createdAt', 'desc')
                            ->get();

            return response()->json([
                'posts' => $posts
            ]);
            
        } catch (\Exception $err) {
            return response()->json([
                'error' => $err->getMessage()
            ], 400);
        }
    }

    public function show(Request $request, $id) {
        try {
            
            $post = Posts::find($id);

            return response()->json([
                'post' => $post
            ]);

        } catch (\Exception $err) {
            return response()->json([
                'error' => $err->getMessage()
            ], 400);
        }
    }
}
