<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, Listing $listing)
    {
        // Seller tidak bisa laporkan iklan sendiri
        if ($listing->isOwnedBy(Auth::user())) {
            return back()->with('error', 'Kamu tidak bisa melaporkan iklan sendiri.');
        }

        // Cegah duplikasi laporan
        $alreadyReported = Report::where('reporter_id', Auth::id())
            ->where('listing_id', $listing->id)
            ->exists();

        if ($alreadyReported) {
            return back()->with('error', 'Kamu sudah pernah melaporkan iklan ini.');
        }

        $request->validate([
            'reason' => 'required|in:spam,fraud,prohibited,other',
            'description' => 'nullable|string|max:500',
        ]);

        Report::create([
            'reporter_id' => Auth::id(),
            'listing_id' => $listing->id,
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Laporan berhasil dikirim. Tim kami akan meninjau iklan ini.');
    }
}
