<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InertiaTest;
use Inertia\Inertia;

class InertiaTestController extends Controller
{
    public function index()
    {
        return Inertia::render('Inertia/Index', [
            'blogs' => InertiaTest::all()
        ]);
    }

    public function create()
    {
        return Inertia::render("Inertia/Create");
    }

    public function show($id)
    {
        return Inertia::render('Inertia/Show', [
            'id' => $id,
            'blog' => InertiaTest::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:20'],
            'content' => ['required', 'string']
        ]);

        InertiaTest::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return to_route('inertia.index')
        ->with([
            'message' => '登録しました。'
        ]);
    }

    public function delete($id)
    {
        $blog = InertiaTest::findOrFail($id);
        $blog->delete();
        
        return to_route('inertia.index')
        ->with([
            'message' => '削除しました。'
        ]);
    }
}
