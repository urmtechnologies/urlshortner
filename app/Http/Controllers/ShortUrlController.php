<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\UrlHit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    private function links(Request $request)
    {
        $query = ShortUrl::query()->with(['user', 'client']);
        if ($request->user()->role === 'admin') {
            $query->where('client_id', $request->user()->client_id);
        } elseif ($request->user()->role === 'member') {
            $query->where('client_id', $request->user()->client_id)->where('user_id', $request->user()->id);
        } else {
            abort_unless($request->user()->role === 'superadmin', 403);
            $request->validate(['client_id' => ['sometimes', 'integer', 'exists:clients,id']]);
            if ($request->filled('client_id')) {
                $query->where('client_id', $request->input('client_id'));
            }
        }
        $period = $request->query('period', 'month');
        if (! in_array($period, ['month', 'last_month', 'last_week', 'today', 'all'], true)) {
            $period = 'month';
        }
        $start = match ($period) {
            'today' => now()->startOfDay(),
            'last_week' => now()->subWeek()->startOfWeek(),
            'last_month' => now()->subMonthNoOverflow()->startOfMonth(),
            'month' => now()->startOfMonth(),
            default => null,
        };
        $end = match ($period) {
            'today' => now()->endOfDay(),
            'last_week' => now()->subWeek()->endOfWeek(),
            'last_month' => now()->subMonthNoOverflow()->endOfMonth(),
            'month' => now()->endOfMonth(),
            default => null,
        };
        if ($start) {
            $query->whereBetween('created_at', [$start, $end]);
            // Hits in the selected period for the links created during that period.
            $query->withCount(['hits as period_hits' => fn ($q) => $q->whereBetween('created_at', [$start, $end])]);
        }

        return [$query, $period];
    }

    public function index(Request $request)
    {
        [$query, $period] = $this->links($request);
        $urls = $query->latest()->paginate(10)->withQueryString();

        return view('short-urls.index', compact('urls', 'period'));
    }

    public function create(Request $request)
    {
        abort_unless(in_array($request->user()->role, ['admin', 'member'], true), 403);

        return view('short-urls.create');
    }

    public function store(Request $request)
    {
        abort_unless(in_array($request->user()->role, ['admin', 'member'], true) && $request->user()->client_id, 403);
        $data = $request->validate(['original_url' => ['required', 'url:http,https', 'max:2048']]);
        do {
            $code = Str::random(10);
        } while (ShortUrl::where('code', $code)->exists());
        $url = ShortUrl::create([
            'client_id' => $request->user()->client_id, 'user_id' => $request->user()->id,
            'code' => $code, 'original_url' => $data['original_url'],
        ]);
        $prefix = $request->user()->role;

        return redirect()->route($prefix.'.short-urls.index')->with('success', 'Short URL created: '.route('short.resolve', $url->code));
    }

    public function download(Request $request)
    {
        [$query, $period] = $this->links($request);

        return response()->streamDownload(function () use ($query, $period) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Short URL', 'Original URL', 'Client', 'Created By', 'Hits', 'Created At']);
            foreach ($query->orderByDesc('id')->cursor() as $url) {
                $safe = fn ($value) => preg_match('/^[=+@\-]/', (string) $value) ? "'".$value : $value;
                fputcsv($out, [route('short.resolve', $url->code), $url->original_url,
                    $safe($url->client?->name), $safe($url->user?->name),
                    $period === 'all' ? $url->hits_count : $url->period_hits, $url->created_at->toDateTimeString()]);
            }
            fclose($out);
        }, 'short-urls-'.$period.'-'.now()->format('Ymd').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function resolve(string $code)
    {
        $url = ShortUrl::where('code', $code)->firstOrFail();
        DB::transaction(function () use ($url) {
            $url->increment('hits_count');
            UrlHit::create(['short_url_id' => $url->id, 'created_at' => now()]);
        });

        return redirect()->away($url->original_url, 302);
    }
}
