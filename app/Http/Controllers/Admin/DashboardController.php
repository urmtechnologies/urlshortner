<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Http\Request;
class DashboardController extends Controller {
    public function index(Request $request) {
        $clientId = $request->user()->client_id;
        $users = User::where('client_id',$clientId)->withCount('shortUrls')->withSum('shortUrls','hits_count')->latest()->limit(5)->get();
        $urls = ShortUrl::where('client_id',$clientId)->with('user')->latest()->limit(5)->get();
        return view('admin.index',compact('users','urls'));
    }
}
