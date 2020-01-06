<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authorsAndBooks = DB::table('authors')
            ->Join('books', 'books.author_id', '=', 'authors.id')
            ->orderBy('authors.name')
            ->simplePaginate(10);

        return view('list', compact('authorsAndBooks'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fiveYearsAfterBirthDate = date('Y-m-d', strtotime($request->birth_date . " +5 year"));

        $request->validate([
            'name' => 'required|max:50',
            'birth_date' => 'required|date|before:now',
            'address' => 'nullable|max:255',
            'title' => 'required|unique:books|max:100',
            'release_date' => 'required|date|after_or_equal:' . $fiveYearsAfterBirthDate
        ]);

        $author = new Author();
        $author->name = $request->name;
        $author->birth_date = $request->birth_date;
        $author->address = $request->address;
        $author->save();

        $book = new Book();
        $book->author_id = $author->id;
        $book->title = $request->title;
        $book->release_date = $request->release_date;
        $book->save();

        return redirect('authors')
            ->with('success', 'Great! New entry created successfully.');
    }

}
