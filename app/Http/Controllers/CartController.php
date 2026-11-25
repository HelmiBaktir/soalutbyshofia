<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $ids = $this->getCart();

        $courses = $ids ? Course::whereIn('id', $ids)->get() : collect();
        $total = $courses->sum('price');

        return view('cart.index', compact('courses', 'total'));
    }

    public function add(Request $request, Course $course)
    {
        if (! auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['redirect' => route('login')], 401);
            }

            return redirect()->route('login')
                ->with('status', 'Silakan login terlebih dahulu untuk menambahkan ke keranjang.');
        }

        $cart = $this->getCart();
        $added = false;

        if (! in_array($course->id, $cart, true)) {
            $cart[] = $course->id;
            session(['cart' => $cart]);
            $added = true;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'added' => $added,
                'count' => count($cart),
                'message' => $course->code.' ditambahkan ke keranjang.',
            ]);
        }

        return back()->with('status', $course->code.' ditambahkan ke keranjang.');
    }

    public function remove(Course $course)
    {
        $cart = array_values(array_filter($this->getCart(), fn ($id) => (int) $id !== (int) $course->id));
        session(['cart' => $cart]);

        return back()->with('status', $course->code.' dihapus dari keranjang.');
    }

    private function getCart(): array
    {
        return array_values(array_filter(array_map('intval', (array) session('cart', []))));
    }
}
