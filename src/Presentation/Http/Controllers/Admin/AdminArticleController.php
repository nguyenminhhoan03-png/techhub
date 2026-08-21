<?php

declare(strict_types=1);

namespace Presentation\Http\Controllers\Admin;

use Domain\Article\Entities\Article;
use Domain\Article\Entities\ContentCategory;
use Domain\Article\Enums\ArticleType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminArticleController
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');
        $type = $request->query('type');

        $query = Article::query()->with(['category', 'author'])->latest('id');

        if (!empty($search)) {
            $query->where('title', 'like', "%{$search}%");
        }
        if (!empty($status)) {
            $query->where('status', $status);
        }
        if (!empty($type)) {
            $query->where('type', $type);
        }

        $articles = $query->paginate(15)->withQueryString();

        return view('admin.articles.index', [
            'articles' => $articles,
        ]);
    }

    public function create(): View
    {
        $categories = ContentCategory::query()->where('is_active', true)->get();

        return view('admin.articles.create', [
            'categories' => $categories,
            'types' => ArticleType::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
            'category_id' => ['required', 'exists:content_categories,id'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content_markdown' => ['required', 'string'],
            'featured_image_url' => ['nullable', 'url', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        $slug = Str::slug($validated['title']);
        $count = Article::query()->where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $validated['slug'] = $slug;
        $validated['author_id'] = Auth::id() ?? 1;
        $validated['content_html'] = nl2br(htmlspecialchars($validated['content_markdown'], ENT_QUOTES, 'UTF-8'));
        $validated['published_at'] = 'published' === $validated['status'] ? now() : null;

        Article::query()->create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Đã tạo bài viết mới thành công!');
    }

    public function edit(int $id): View
    {
        $article = Article::query()->findOrFail($id);
        $categories = ContentCategory::query()->where('is_active', true)->get();

        return view('admin.articles.edit', [
            'article' => $article,
            'categories' => $categories,
            'types' => ArticleType::cases(),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $article = Article::query()->findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
            'category_id' => ['required', 'exists:content_categories,id'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content_markdown' => ['required', 'string'],
            'featured_image_url' => ['nullable', 'url', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        $validated['content_html'] = nl2br(htmlspecialchars($validated['content_markdown'], ENT_QUOTES, 'UTF-8'));
        if ('published' === $validated['status'] && empty($article->published_at)) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function toggle(int $id): RedirectResponse
    {
        $article = Article::query()->findOrFail($id);
        $newStatus = ('published' === $article->status) ? 'draft' : 'published';
        $article->update([
            'status' => $newStatus,
            'published_at' => ('published' === $newStatus) ? now() : $article->published_at,
        ]);

        return back()->with('success', "Đã chuyển trạng thái bài viết thành: {$newStatus}");
    }

    public function destroy(int $id): RedirectResponse
    {
        $article = Article::query()->findOrFail($id);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Đã xóa bài viết.');
    }
}
