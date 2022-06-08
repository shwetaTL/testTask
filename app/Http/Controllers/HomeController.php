<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserConnection;
use Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $limit = 10;
        $user = Auth::user();

        $suggestionConnects = DB::select("SELECT * FROM( select main_users.name, main_users.email, main_users.id, new_table.receiver, 
                      new_table.sender  from  users as main_users LEFT JOIN (
  select users.name, users.email, users.id , user_connections.receiver , user_connections.sender  from `user_connections` join `users` on
    `users`.`id` = `user_connections`.`sender` and (`user_connections`.`sender` = $user->id or `user_connections`.`receiver` = $user->id) 
) as new_table ON  (main_users.id= new_table.receiver) WHERE main_users.id != $user->id) as new_table_2 WHERE new_table_2.sender is null 
                                        and new_table_2.receiver is null limit $limit offset 0;");

        $suggestions = $suggestionConnects;

        return view('home', compact('suggestions'));
    }
}
