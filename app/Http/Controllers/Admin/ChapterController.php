<?php
namespace App\Http\Controllers\Admin;

use App\Model\Book;
use App\Model\Chapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChapterController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $book = Book::find($request->get('book_id'));

        return view('admin.chapter.index', [
            'book' => $book,
            'data' => $book->chapters()->orderBy('order')->get()
        ]);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        return view('admin.chapter.edit', ['item' => Chapter::find($id)]);
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $chapter = Chapter::find($id);
        $chapter->update($request->all());
        if (!$request->expectsJson()) {
            return redirect("chapter?book_id=$chapter->book_id");
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        $chapter = Chapter::find($id);
        $chapter->sections()->delete();
        $chapter->delete();
    }
}
