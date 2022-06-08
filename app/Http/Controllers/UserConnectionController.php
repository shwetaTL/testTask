<?php

namespace App\Http\Controllers;

use App\Models\UserConnection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getSuggestion(Request $request)
    {
        try {
            $limit = 10;
            $page = $request->get('page');
            $user = Auth::user();
            $suggestionConnects = UserConnection::rightJoin('users', function($join) use($user){
                $join->on('users.id', '=', 'user_connections.sender');
                $join->where('user_connections.sender', '!=', $user->id );
                $join->where('user_connections.receiver', '!=', $user->id );
            })
                ->where('users.id', '!=', $user->id)
                ->select(DB::raw('users.name, users.email, users.id'))->paginate($limit);
            return new JsonResponse(['status' => true, 'data' => $suggestionConnects]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => false, 'error' => $e->getMessage()]);
        }
    }

    public function getSentRequests(Request $request)
    {
        try {
            $user = Auth::user();
            $limit = 10;
            $page = $request->get('page');
            $requests = UserConnection::where(['sender' => $user->id , 'status' => UserConnection::REQUESTED])
                            ->with('sentRequestsUser')->paginate($limit);
            return new JsonResponse(['status' => true, 'data' => $requests]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => false, 'error' => $e->getMessage()]);
        }
    }

    public function getReceivedRequests(Request $request)
    {
        try {
            $user = Auth::user();
            $limit = 10;
            $page = $request->get('page');
            $requests = UserConnection::where(['receiver' => $user->id , 'status' => UserConnection::REQUESTED])
                ->with('receivedRequestsUser')->paginate($limit);
            return new JsonResponse(['status' => true, 'data' => $requests]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => false, 'error' => $e->getMessage()]);
        }
    }

    public function getConnections(Request $request)
    {
        $user = Auth::user();
        $limit = 10;
        $page = $request->get('page');

        $connections = DB::select("SELECT * FROM (
                        select users.name, users.email, users.id , user_connections.id as connection_id from `user_connections` inner join 
                        `users` on `users`.`id` = `user_connections`.`sender` and 
                        `user_connections`.`receiver` = $user->id and `user_connections`.`status` = 1
                        UNION
                        select users_rec.name, users_rec.email, users_rec.id , user_connections_rec.id as connection_id from `user_connections` as `user_connections_rec` inner join 
                        `users` as `users_rec` on `users_rec`.`id` = `user_connections_rec`.`receiver` and 
                        `user_connections_rec`.`sender` = $user->id and `user_connections_rec`.`status` = 1
                        
                        ) AS connection_table");

        return new JsonResponse(['status' => true, 'data' => $connections]);


    }

    public function sendRequest(Request $request)
    {
        try {
            $data = json_decode($request->getContent());
            UserConnection::create([
                'sender' => Auth::user()->id,
                'receiver' => $data->userId,
                'status' => UserConnection::REQUESTED
            ]);
            return new JsonResponse(['status' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => false, 'error' => $e->getMessage()]);
        }

    }

    public function deleteRequest(Request $request)
    {
        try {
            $data = json_decode($request->getContent());
            UserConnection::where([
                'sender' => $data->userId,
                'receiver' => $data->requestedUserId
            ])->delete();
            return new JsonResponse(['status' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => false, 'error' => $e->getMessage()]);
        }
    }

    public function acceptRequest(Request $request)
    {
        try {
            $data = json_decode($request->getContent());
            UserConnection::where([
                'sender' => $data->requestedUserId,
                'receiver' => $data->userId
            ])->update(['status' => UserConnection::CONNECTED]);
            return new JsonResponse(['status' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => false, 'error' => $e->getMessage()]);

        }
    }

    public function removeConnection(Request $request)
    {
        try {
            $data = json_decode($request->getContent());
            UserConnection::where([
                'id' => $data->connectionId
            ])->delete();
            return new JsonResponse(['status' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => false, 'error' => $e->getMessage()]);

        }
    }
}
