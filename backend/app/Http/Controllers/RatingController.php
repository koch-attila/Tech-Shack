<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Models\Rating;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Rating::with('product', 'user')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRatingRequest $request)
    {
        $validated = $request->validated();

        return Rating::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        return $rating->load('product', 'user');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        $validated = $request->validated();

        $rating->update($validated);
        return $rating->load('product', 'user');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        $rating->delete();
        return response()->noContent();
    }

    public function user(Rating $rating)
    {
        return response()->json($rating->user);
    }
}
