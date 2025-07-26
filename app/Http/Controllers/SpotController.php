<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpotController extends Controller
{
    public function search(Request $request)
{
    $query = Spot::query();

    if ($request->filled('prefecture')) {
        $query->where('area', $request->prefecture); // ← area に県名が入っているならOK
    }

    $spots = $query->get();

    if ($spots->isEmpty()) {
        return view('spots.search')->with('message', '温泉データが見つかりませんでした。');
    }

    return view('spots.search', compact('spots'));
}
}
