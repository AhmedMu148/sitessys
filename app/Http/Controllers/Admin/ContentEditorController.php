<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TplLayout;
use App\Models\TplPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContentEditorController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show active header/footer and active sections with edit options.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $site = Site::where('user_id', $user->id)->where('status_id', true)->first();

        if (!$site) {
            abort(404, 'No active site found');
        }

        $activeHeader = $site->active_header_id
            ? TplLayout::where('id', $site->active_header_id)->first()
            : null;

        $activeFooter = $site->active_footer_id
            ? TplLayout::where('id', $site->active_footer_id)->first()
            : null;

        // Load pages with their active sections
        $pages = TplPage::where('site_id', $site->id)
            ->with(['sections' => function ($q) {
                $q->where('status', true)->orderBy('sort_order');
            }])
            ->orderBy('name')
            ->get();

        return view('admin.content.index', compact('site', 'activeHeader', 'activeFooter', 'pages'));
    }
}
