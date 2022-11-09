<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('items.index', [
            'items' => Item::orderBy('obtained', 'desc')->paginate(12)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Item::class);

        return view('items.create', [
            'labels' => Label::all()->where('display', true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Item::class);

        $validated = $request->validate(
            [
                'name' => ['required', 'min:2'],
                'description' => ['required'],
                'obtained' => ['required', 'date'],
                'image' => ['nullable', 'file', 'image', 'max:4096'],
                'labels' => ['nullable', 'array'],
                'labels.*' => ['numeric', 'integer', 'exists:labels,id'],
            ]
        );

        // filename
        $fn = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $fn = 'ci_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->put($fn, $file->get());
        }

        $item = new Item();
        $item->name = $validated['name'];
        $item->description = $validated['description'];
        $item->obtained = $validated['obtained'];
        $item->image = $fn;
        $item->save();

        if (isset($validated['labels'])) {
            $item->labels()->sync($validated['labels']);
        }

        Session::flash("success", "Item " . $validated['name'] . " created");

        return Redirect::route('items.show', $item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return view('items.show', [
            'item' => $item
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $this->authorize('update', $item);
        return view('items.edit', [
            'item' => $item,
            'labels' => Label::all()->where('display', true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        $validated = $request->validate(
            [
                'name' => ['required', 'min:2'],
                'description' => ['required'],
                'obtained' => ['required', 'date'],
                'image' => ['nullable', 'file', 'image', 'max:4096'],
                'labels' => ['nullable', 'array'],
                'labels.*' => ['numeric', 'integer', 'exists:labels,id'],
            ]
        );

        // filename
        $fn = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $fn = 'ci_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->put($fn, $file->get());
        }

        $item = Item::find($item->id);
        $item->name = $validated['name'];
        $item->description = $validated['description'];
        $item->obtained = $validated['obtained'];
        $item->image = $fn == null ? $item->image : $fn;
        $item->save();

        $newLabels = isset($validated['labels']) ? $validated['labels'] : [];
        $item->labels()->sync($newLabels);

        Session::flash("success", "Item " . $validated['name'] . " updated");

        return Redirect::route('items.show', $item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        Session::flash("success", "Item " . $item->name . " deleted");

        return Redirect::route('items.index');
    }
}
