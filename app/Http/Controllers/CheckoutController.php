<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $ids = $request->query('course_ids', session('cart', []));

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

        $ids = array_values(array_filter(array_unique(array_map('intval', (array) $ids))));

        $courses = $ids ? Course::whereIn('id', $ids)->get() : collect();
        $total = $courses->sum('price');

        return view('checkout.index', compact('courses', 'total', 'ids'));
    }
}
