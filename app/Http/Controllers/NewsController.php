<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\News;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    use ResponseFormattingTrait;
    public function getBySlug($slug)
    {
        $news = News::where('slug', $slug)->first();

        if (!$news) {
            $response = $this->_formatBaseResponse(404, null, 'Không tìm thấy news với slug này');
        } else {
            $categoryTitle = $news->category->title;
            $news->category_title = $categoryTitle;
            $authorName = $news->author->name;
            $news->author_name = $authorName;
            $response = $this->_formatBaseResponse(200, $news, 'Lấy dữ liệu thành công');
        }

        return response()->json($response);
    }
    public function searchNews(Request $request)
    {
    $query = News::query()->where('status', 1);
    $filters = $request->input('filters', []);

    foreach ($filters as $filter) {
        $field = $filter['field'];
        $value = $filter['value'];

        if (!empty($value)) {
            $query->where($field, 'like', '%' . $value . '%');
        }
    }

    $page = $request->input('page', 1) + 1;
    $size = $request->input('size', 10);
    $sorts = $request->input('sorts', []);

    foreach ($sorts as $sort) {
        $field = $sort['field'];
        $direction = $sort['direction'];

        if (!empty($field) && !empty($direction)) {
            $query->orderBy($field, $direction);
        }
    }

    $news = $query->paginate($size, ['*'], 'page', $page);
    $transformedNews = [];
    foreach ($news as $article) {
        $transformedArticle = [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'description' => $article->description,
            'content' => $article->content,
            'image' => url(env("APP_URL") . '/storage/' . $article->image),
            'created_at' => $article->created_at,
            'updated_at' => $article->updated_at,
            'published_at' => $article->published_at,
            'category_title' => $article->category->title,
            'author_name' => $article->author->name
        ];
        $transformedNews[] = $transformedArticle;
    }

    $totalPages = ceil($news->total() / $size);
    return response()->json($this->_formatCountResponse(
        $transformedNews,
        $news->perPage(),
        $totalPages,
    ));
}

}
