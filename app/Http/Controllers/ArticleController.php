<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * 
     *     Get list of articles
     * 
     *     @param  mixed  $request
     *     @return array
     * 
     */
    public function index(Request $request)
    {
        $articales = Article::query();

        if ($request->filled('keyword')) {
            $articales->where('title', 'like', "%{$request->keyword}%");
        }

        if ($request->filled('date')) {
            $articales->whereDate('date', $request->date);
        }

        if ($request->filled('category')) {
            $articales->where('category', $request->category);
        }

        if ($request->filled('source')) {
            $articales->where('source', $request->source);
        }

        return $articales->paginate(10);
    }

    /**
     * 
     *     Get single of article
     * 
     *     @param  integer  $id
     *     @return Object|null
     * 
     */
    public function show($id)
    {
        return Article::findOrFail($id);
    }
}
