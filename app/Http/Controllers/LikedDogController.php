<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\likedDog;
use Illuminate\Support\Facades\Auth;

class LikedDogController extends Controller
{
    public function index(){
        $user = auth()->user();
        $favoriteDogs = $user->favoriteDogs->pluck('dog')->toArray(); // Fetch the favorite dogs
        
        
        return view('welcome', compact('favoriteDogs'));
    }

    public function fetchDogBreeds()
    {
        $response = Http::withOptions(['verify' => false])->get('https://dog.ceo/api/breeds/list/all');
        $breeds = $response->json()['message'];

        $dogData = [];

        // Fetch a random image for each breed
        foreach ($breeds as $breed => $subBreeds) {
            $imageResponse = Http::withOptions(['verify' => false])->get("https://dog.ceo/api/breed/{$breed}/images/random");
            $dogData[] = [
                'name' => ucfirst($breed),
                'image' => $imageResponse->json()['message'],
            ];
        }

        return response()->json($dogData);
    }

    public function selectFavoriteDogs(Request $request)
    {
        $selectedDogs = $request->dogs;
        $liked = $request->liked;

        if($liked == 'true'){
            likedDog::where('user_id',auth::id())->where('dog', $selectedDogs)->delete();
            return response()->json(['message' => 'you unlike '.$selectedDogs.' breed']);
        }else{
            likedDog::create([
                'user_id' => Auth::id(),
                'dog' => $selectedDogs
            ]);
            return response()->json(['message' => 'you like '.$selectedDogs.' breed']);
        }
            
            
        

        
    }
}
