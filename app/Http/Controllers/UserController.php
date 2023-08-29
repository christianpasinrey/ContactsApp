<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Http\Controllers\ContactDataController;

class UserController extends Controller
{

    public function index()
    {
        return response()
            ->json(User::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string'
        ]);

        $user = User::create($validated);
        if($request->contacts){
            $contact_data = new ContactDataController();
            $model_string = 'App\Models\User';
            foreach($request->contacts as $contact){
                $contact_data->store($contact, $model_string, $user);
            }

            $authUser = User::find(auth()->user()->id);
            $authUser->contacts()->attach($user->id);
        }

        return response()
            ->json([
                'message' => 'User created successfully',
                'data' => $user->load('contactData')
            ], 200);
    }

    public function show(User $user)
    {
        return response()
            ->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string'
        ]);

        $user->update($validated);

        if($request->contacts) {

        }
        return response()
            ->json($user, 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()
            ->json([
                'message' => 'User deleted successfully'
            ]);
    }

    public function getContacts()
    {
        $user = User::find(auth()->user()->id);
        $contacts = $user->contacts()->get();

        return response()
            ->json($contacts);
    }

    public function searchContacts($search_string = null)
    {
        $user = User::find(auth()->user()->id);
        $contacts = $user->contacts();
        if(!empty($search_string)){
            $contacts->where('name', 'like', '%'.$search_string.'%')
                ->orWhere(function($query) use ($search_string){
                    $query->whereHas('contactData', function($query) use ($search_string){
                        $query->where('label', 'like', '%'.$search_string.'%')
                            ->orWhere('value', 'like', '%'.$search_string.'%');
                    });
                });
        }

        return response()
            ->json($contacts->orderBy('name', 'asc')->paginate(15));
    }

    public function searchUsers($search_string = null)
    {
        empty($search_string) ?
        $users = User::searchable() :
        $users = User::searchable()
            ->name($search_string)
            ->contactData($search_string);

        return response()
            ->json(
                $users->orderBy('name', 'asc')
                    ->get()
            );
    }

    public function addContact(Request $request)
    {
        $user = User::find(auth()->user()->id);

        $contact = User::find($request->id);

        $user->contacts()->attach($contact->id);

        return response()
            ->json([
                'message' => 'Contact added successfully'
            ]);
    }

    public function getUserTimeLinePosts(User $user)
    {
        //return posts of auth user and posts of every contact of auth user paginated
        $authUser = User::find(auth()->user()->id);
        $contacts = $authUser->contacts()->get();
        $contacts_ids = $contacts->pluck('id')->toArray();
        $contacts_ids[] = $authUser->id;
        $paginated_posts = Post::with('user','mentionedUsers','files','comments')
            ->whereIn('user_id', $contacts_ids)
            ->orderBy('created_at', 'desc')
            ->paginate(5)->withPath('users/'.$user->id.'/timeline');

        //change path of paginated posts to users/{id}/timeline

        return $paginated_posts;
    }
}
