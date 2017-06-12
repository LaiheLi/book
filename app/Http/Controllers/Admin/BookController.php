<?php
namespace App\Http\Controllers\Admin;

use DB;
use File;
use Excel;
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
            'data'     => Book::catalog($request->get('catalog'))->orderBy('handle')->orderBy('name')->paginate(10),
            'catalogs' => Book::groupBy('catalog')->get([DB::raw('DISTINCT(catalog)'), DB::raw('count(id) as num')])
        ]);
    }

    public function export()
    {
        ini_set('memory_limit', '1024m');
        Excel::create('book ' . date('Y-m-d H:i:s'), function ($excel) {
            $excel->sheet('book', function ($sheet) {
                $sheet->setWidth(array(
                    'A'     =>  10,
                    'C'     =>  40,
                    'D'     =>  10,
                    'E'     =>  60,
                    'F'     =>  30,
                    'K'     =>  100
                ));
                $sheet->fromArray(Book::handle()->orderBy('handle')->orderBy('name')->take(2000)->get()->map(function ($item) {
                    return [
                        'id'               => $item->id,
                        'tosortid'         => NULL,
                        'name'             => $item->name,
                        'type'             => $item->catalog,
                        'cover'            => '/resource_library/电子书/封面/' . $item->cover,
                        'author'           => $item->author,
                        'origin'           => NULL,
                        'copyright'        => NULL,
                        'url'              => NULL,
                        'size'             => NULL,
                        'description'      => $item->description,
                        'content'          => NULL,
                        'm_contents'       => NULL,
                        'm_resources'      => NULL,
                        'm_resources_url'  => NULL,
                        'm_sortid'         => NULL,
                        'm_clicks'         => NULL,
                        'm_like'           => NULL,
                        'm_createdate'     => NULL,
                        'm_createdatetime' => NULL,
                        'm_updatedate'     => NULL,
                        'm_updatedatetime' => NULL
                    ];
                })->toArray());
            });
        })->download('xlsx');
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
