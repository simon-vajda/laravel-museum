<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
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
        $this->authorize('create', Comment::class);

        $validated = $request->validate(
            [
                'text' => ['required'],
                'item' => ['required', 'exists:items,id'],
            ],
            [
                'text.required' => 'The comment cannot be empty',
            ]
        );

        $item = Item::where('id', $validated['item'])->first();

        $comment = new Comment();
        $comment->text = $validated['text'];
        $comment->author()->associate(Auth::user());
        $comment->item()->associate($validated['item']);
        $comment->save();

        return Redirect::route('items.show', $item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate(
            [
                'update_text' => ['required'],
                'comment_id' => ['required'],
            ],
            [
                'update_text.required' => 'The comment cannot be empty',
            ]
        );

        $comment->text = $validated['update_text'];
        $comment->save();

        Session::flash('success', 'Comment updated successfully');

        return Redirect::route('items.show', $comment->item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        Session::flash("success", "Comment deleted");
        return Redirect::route('items.show', $comment->item);
    }
}
