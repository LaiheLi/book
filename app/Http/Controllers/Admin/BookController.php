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
            'data'     => Book::catalog($request->get('catalog'))
                              ->orderBy('handle')
                              ->orderBy('export', 'desc')
                              ->orderBy('name')
                              ->paginate(10),
            'catalogs' => Book::groupBy('catalog')->get([DB::raw('DISTINCT(catalog)'), DB::raw('count(id) as num')])
        ]);
    }

    public function export()
    {
        ini_set('memory_limit', '1024m');
        Excel::create('book ' . date('Y-m-d H:i:s'), function ($excel) {
            $excel->sheet('book', function ($sheet) {
                $sheet->setWidth(array(
                    'A' => 10,
                    'C' => 40,
                    'D' => 10,
                    'E' => 60,
                    'F' => 30,
                    'K' => 100
                ));
                $sheet->fromArray(Book::handle()
                                      ->orderBy('handle')
                                      ->orderBy('name')
                                      ->take(2000)
                                      ->get()
                                      ->map(function ($item) {
                                          return [
                                              'id'               => $item->id,
                                              'tosortid'         => NULL,
                                              '书名'               => $item->name,
                                              '类别'               => $item->catalog,
                                              '封面'               => '/resource_library/电子书/封面/' . $item->cover,
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
                                      })
                                      ->toArray());
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

    /**
     * 测试书籍是否可以下载
     *
     * @param $id
     *
     * @return mixed
     */
    public function test($id)
    {
        $book  = Book::find($id);
        $cover = $this->encode(config('book.image_path') . '/' . $book->cover);
        //没有封面，不处理
        if (!File::exists($cover)) {
            return response()->json(['status' => FALSE, 'message' => '书籍封面未找到']);
        }
        foreach ($book->sections as $section) {
            if (!File::exists($this->encode($section->path))) {
                return response()->json(['status' => FALSE, 'message' => "节：$section->id：$section->name 未找到txt文件"]);
            }
        }

        return response()->json(['status' => TRUE]);
    }
}
