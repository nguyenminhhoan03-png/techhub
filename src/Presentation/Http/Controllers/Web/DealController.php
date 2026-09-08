<?php

declare(strict_types=1);

namespace Presentation\Http\Controllers\Web;

use Domain\Deal\Entities\DigitalDeal;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DealController
{
    public function index(Request $request): View
    {
        $category = $request->query('category');
        $search   = trim((string) $request->query('q', ''));

        $query = DigitalDeal::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id');

        if ($category && is_string($category)) {
            $query->where('category', $category);
        }

        if ('' !== $search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $deals = $query->paginate(12)->withQueryString();

        $categories = DigitalDeal::query()
            ->where('is_active', true)
            ->select('category')
            ->distinct()
            ->pluck('category');

        $featuredDeals = DigitalDeal::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        return view('pages.deals.index', [
            'deals'         => $deals,
            'categories'    => $categories,
            'activeCategory' => $category,
            'search'        => $search,
            'featuredDeals' => $featuredDeals,
        ]);
    }

    public function show(string $slug): View
    {
        /** @var DigitalDeal $deal */
        $deal = DigitalDeal::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedDeals = DigitalDeal::query()
            ->where('is_active', true)
            ->where('id', '!=', $deal->id)
            ->where('category', $deal->category)
            ->take(4)
            ->get();

        if ($relatedDeals->isEmpty()) {
            $relatedDeals = DigitalDeal::query()
                ->where('is_active', true)
                ->where('id', '!=', $deal->id)
                ->take(4)
                ->get();
        }

        return view('pages.deals.show', [
            'deal'         => $deal,
            'relatedDeals' => $relatedDeals,
        ]);
    }
}
