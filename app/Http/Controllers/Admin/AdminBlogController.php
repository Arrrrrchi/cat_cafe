<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Cat;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRquest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{
    /**
     * （管理者用）ブログ一覧画面表示
     */
    public function index()
    {
        $blogs = Blog::latest('updated_at')->paginate(10);

         //第二引数に連想配列を指定することで変数定義が可能（$blogs = Blog::latest('updated_at')->paginate(10);)
        return view('admin.blogs.index', ['blogs' => $blogs]);
    }

    /**
     * （管理者用）ブログ投稿画面表示
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * （管理者用）ブログ投稿処理
     */
    public function store(StoreBlogRequest $request)
    {
        $savedImagePath = $request->file('image')->store('blogs', 'public'); // フォームから送信された画像をblogsディレクトリに保存

        /* データベースに保存 */
        $blog = new Blog($request->validated()); // 引数にフォームから送信された連想配列のデータをvalidated()メソッドで検証したものを与えます
        $blog->image = $savedImagePath; // 'image'のプロパティに上で画像を保存した時のパスを代入
        $blog->save(); // データベースに追加

        return to_route('admin.blogs.index')->with('success', 'ブログを投稿しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * （管理者用）指定したIDブログの編集画面
     */
    public function edit(Blog $blog) // モデルのタイプヒンティングを使った省略記法
    {
        $categories = Category::all();
        $cats = Cat::all();
        return view('admin.blogs.edit', ['blog' => $blog, 'categories' => $categories, 'cats' => $cats]); //[]内はview内で使える変数の定義
    }

    /**
     * （管理者用）指定したブログの更新処理
     */
    public function update(UpdateBlogRquest $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $updateData = $request->validated();

        //画像を変更する場合
        if ($request->has('image')) {
            // 変更前の画像を削除
            Storage::disk('public')->delete($blog->image);
            // 変更後の画像をアップロード、保存パスを更新対象データにセット
            $updateData['image'] = $request->file('image')->store('blogs', 'public');
        }
        $blog->category()->associate($updateData['category_id']);
        $blog->cats()->sync($updateData['cats'] ?? []);
        $blog->update($updateData);

        return to_route('admin.blogs.index')->with('success', 'ブログを更新しました');
    }

    /**
     * （管理者用）指定したIDブログの削除処理
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        Storage::disk('public')->delete('$blog->image');

        return to_route('admin.blogs.index')->with('success', 'ブログを削除しました');
    }
}
