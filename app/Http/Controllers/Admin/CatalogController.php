<?php
namespace App\Http\Controllers\Admin;

use DB;
use App\Model\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CatalogController extends Controller
{
    /**
     * 编辑分类
     *
     * @return mixed
     */
    public function index()
    {
        return view('admin.catalog.index', [
            'data' => Book::groupBy('catalog')->get([
                DB::raw('DISTINCT(catalog)'),
                DB::raw('count(id) as num')
            ])
        ]);
    }

    /**
     * 更新所有书籍的分类
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $catalogs = $request->get('catalogs');
            foreach ($request->get('data') as $key => $item) {
                if ($catalog = $catalogs[$key]) {
                    DB::table('books')->where('catalog', $item)->update(['catalog' => $catalog]);
                }
            }
        });

        return redirect('catalog');
    }
}
