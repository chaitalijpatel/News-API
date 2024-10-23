<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\UserPreference;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    /**
     * 
     *     Set user preferences
     * 
     *     @param  mixed  $request
     *     @return json
     * 
     */
    public function setPreferences(Request $request)
    {
        $request->validate([
            'preferred_sources' => 'array',
            'preferred_categories' => 'array',
        ]);

        $preferences = UserPreference::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'preferred_sources' => json_encode($request->preferred_sources),
                'preferred_categories' => json_encode($request->preferred_categories),
            ]
        );

        return response()->json($preferences);
    }

    /**
     * 
     *     Getuser preferences
     * 
     *     @param  mixed  $request
     *     @return json
     * 
     */
    public function getPreferences(Request $request)
    {
        $userPreferences = UserPreference::where('user_id', $request->user()->id)->first();

        if ($userPreferences) {
            return response()->json($userPreferences);
        }

        return response()->json(['message' => 'Not found'], 404);
    }

    /**
     * 
     *     Get Personalized Feed
     * 
     *     @param  mixed  $request
     *     @return json
     * 
     */
    public function personalizedFeed(Request $request)
    {
        $preferences = $request->user()->preferences;
        $article = Article::query();

        if ($preferences) {
            if ($preferences->preferred_sources) {
                $sources = json_decode($preferences->preferred_sources);
                $article->whereIn('source', $sources);
            }
            if ($preferences->preferred_categories) {
                $categories = json_decode($preferences->preferred_categories);
                $article->whereIn('category', $categories);
            }
        }

        return response()->json($article->paginate(10));
    }
}
