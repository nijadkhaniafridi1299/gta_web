<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::where('active', true)->with('category')->get();
        return view('home', compact('featuredProducts'));
    }

    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'price' => ['nullable', 'in:all,under-25,25-50,50-100,over-100'],
            'sort' => ['nullable', 'in:newest,price-low,price-high,name'],
        ]);

        $products = Product::query()
            ->where('active', true)
            ->with('category')
            ->when($filters['q'] ?? null, fn ($query, $term) => $query->where('name', 'like', "%{$term}%"))
            ->when($filters['category'] ?? null, fn ($query, $slug) => $query->whereHas('category', fn ($category) => $category->where('slug', $slug)))
            ->when(($filters['price'] ?? 'all') === 'under-25', fn ($query) => $query->where('price', '<', 25))
            ->when(($filters['price'] ?? 'all') === '25-50', fn ($query) => $query->whereBetween('price', [25, 50]))
            ->when(($filters['price'] ?? 'all') === '50-100', fn ($query) => $query->whereBetween('price', [50, 100]))
            ->when(($filters['price'] ?? 'all') === 'over-100', fn ($query) => $query->where('price', '>', 100));

        match ($filters['sort'] ?? 'newest') {
            'price-low' => $products->orderBy('price'),
            'price-high' => $products->orderByDesc('price'),
            'name' => $products->orderBy('name'),
            default => $products->latest(),
        };

        $categories = Category::where('active', true)->orderBy('name')->get();

        return view('products.index', [
            'products' => $products->paginate(12)->withQueryString(),
            'categories' => $categories,
        ]);
    }

    public function show(Product $product)
    {
        abort_unless($product->active, 404);

        return view('products.show', compact('product'));
    }

    /**
     * Generate dynamic Google & Bing XML Sitemap.
     */
    public function sitemap()
    {
        $products = Product::where('active', true)->latest()->get();
        $categories = Category::where('active', true)->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        // Static core routes
        $staticPages = [
            ['url' => route('home'), 'freq' => 'daily', 'prio' => '1.0'],
            ['url' => route('products.index'), 'freq' => 'daily', 'prio' => '0.9'],
            ['url' => route('help'), 'freq' => 'weekly', 'prio' => '0.8'],
            ['url' => route('contact'), 'freq' => 'monthly', 'prio' => '0.5'],
        ];

        foreach ($staticPages as $p) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($p['url']) . "</loc>\n";
            $xml .= '    <lastmod>' . date('Y-m-d') . "</lastmod>\n";
            $xml .= '    <changefreq>' . $p['freq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $p['prio'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        // Category routes
        foreach ($categories as $cat) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars(route('products.index', ['category' => $cat->slug])) . "</loc>\n";
            $xml .= '    <lastmod>' . date('Y-m-d') . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.85</priority>\n";
            $xml .= "  </url>\n";
        }

        // Product routes with rich images
        foreach ($products as $prod) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars(route('products.show', $prod->slug)) . "</loc>\n";
            $xml .= '    <lastmod>' . ($prod->updated_at ? $prod->updated_at->format('Y-m-d') : date('Y-m-d')) . "</lastmod>\n";
            $xml .= "    <changefreq>daily</changefreq>\n";
            $xml .= "    <priority>0.95</priority>\n";
            $imgUrl = asset('images/products/' . $prod->slug . '.svg');
            $xml .= "    <image:image>\n";
            $xml .= '      <image:loc>' . htmlspecialchars($imgUrl) . "</image:loc>\n";
            $xml .= '      <image:title>' . htmlspecialchars($prod->name) . "</image:title>\n";
            $xml .= '      <image:caption>' . htmlspecialchars("Buy {$prod->name} digital game key with instant crypto delivery") . "</image:caption>\n";
            $xml .= "    </image:image>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'X-Robots-Tag' => 'noindex'
        ]);
    }
}
