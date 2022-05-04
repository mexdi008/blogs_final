<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Middleware\ValidateSignature;
use Spatie\RouteDiscovery\Attributes\Route;

class BlogController extends Controller
{
    public function index(){
        $showBlog = Blogs::all();
        return response()->json([
            $showBlog
        ]);
    }

    public function show($uniq_id)
    {
        $showBlogById = Blogs::where("uniq_id",$uniq_id)->first();
        return response($showBlogById);
    }

    public function store(Request $request)
    {
        if($request->title == null)
        {
            return response()->json([
                'Message:'=>'Title boş ola bilməz',
            ]);
        }

        if($request->content == null)
        {
            return response()->json([
                'Message:'=>'Content boş ola bilməz',
            ]);
        }

        if($request->is_active == null)
        {
            return response()->json([
                'Message:'=>'Acive boş ola bilməz',
            ]);
        }

        $uniq_id = Str::uuid();

        $createBlog = Blogs::create([
            "uniq_id" => $uniq_id,
            "title" => $request->title,
            "content" => $request->content,
            "isActive" => $request->is_active,
        ]);
        
    
        if($createBlog == true)
        {
            return response()->json([
                'Message :'=>'Yeni Blog ugurla elave edildi',
                'uniq_id' => $uniq_id,
                'title:'=> $request->title,
                'content:'=>$request->content,
                'is_active:'=>$request->is_active,
                'Time'=>Carbon::now()->format('d-M-Y'),
            ]);
        }
        else
        {
            return response()->json([
                'Message:'=>'Blogu daxil etmek mumkun olmadi',
            ]);
        }
    }

    public function register(Request $request)
    {

        $roles = Role::all();
        $validated = $request->validate([
        'name' => 'required',
        'email' => 'required',
        'password' => 'required',
    ]);
          $user = new User();
          $user->name = $request->name;
          $user->email = $request->email;
          $user->password = bcrypt($request->password);
          $user-> syncRoles($request->role);
          $user->save();
          $token = $user->createToken('token_name')->plainTextToken;
          $response = ['token'=>$token, 'user'=> $user];
          return response ($response, 201);
    }
 
         public function update(Request $request, $uniq_id)
         {
             

               $now = Carbon::now()->format('Y-m-d H:i:s');
               $affected = DB::table('blogs')
              ->where('uniq_id', $uniq_id)
              ->update(['title' => $request->title,'content' => $request->content,'isActive'=> $request->is_active, 'updated_at' => $now]);
            if($affected == true)
            {
                $updated = DB::table('blogs')
                                ->select('updated_at')
                                ->where('uniq_id', $uniq_id)
                                ->get();
                return response()->json([
                'message' => 'Melumatlar ugurla yenilendi',
                'title' => $request->title,
                'content' => $request->content,
                'isActive'=>$request->is_active,
                'updated_at' => $now,

            ]);
            }
            else
            {
                return response()->json([
                    'message' => 'Melumatlari yenilemek ugursuz oldu',
                ]);
            }
              
         }

        public function destroy ($uniq_id)
        {
            $delete = DB::table('blogs')->where('uniq_id', $uniq_id)->delete();
            if($delete == true)
            {
                 return response()->json([
                'message' => 'Melumat ugurla silindi',
            ]);
            }

            else
            {
                return response()->json([
                    'Message :' => 'Melumati silmek ugursuz oldu',
                ]);
            }
            
        }  
    
}
