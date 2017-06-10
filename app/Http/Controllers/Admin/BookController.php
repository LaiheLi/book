<?php
namespace App\Http\Controllers\Admin;

use DB;
use File;
use Image;
use App\Model\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /**
     * 书籍列表
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        return view('admin.book.index', [
            'data'     => Book::catalog($request->get('catalog'))->orderBy('handle')->paginate(20),
            'catalogs' => Book::groupBy('catalog')->get([DB::raw('DISTINCT(catalog)'), DB::raw('count(id) as num')])
        ]);
    }

    /**
     * 编辑书籍
     *
     * @param $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        return view('admin.book.edit', ['item' => Book::find($id)]);
    }

    /**
     * 更新书籍信息
     *
     * @param         $id
     * @param Request $request
     *
     * @return mixed
     */
    public function update($id, Request $request)
    {
        Book::find($id)->update($request->all());

        return redirect('book');
    }

    /**
     * 删除书籍
     *
     * @param $id
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        $book->chapters()->delete();
        $book->sections()->delete();
        $book->delete();
    }

    /**
     * 确认/取消确认书籍
     *
     * @param $id
     */
    public function handle($id)
    {
        $book = Book::find($id);
        $book->update(['handle' => $book->handle ? 0 : 1]);
    }

    /**
     * 图片封面
     *
     * @param $id
     *
     * @return mixed
     */
    public function image($id)
    {
        $file = config('book.image_path') . '/' . Book::find($id)->cover;
        $file = mb_convert_encoding($file, 'gb2312', "utf-8");
        if (File::exists($file)) {
            return Image::make($file)->response();
        }
    }
}
