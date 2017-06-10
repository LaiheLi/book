<?php
namespace App\Http\Controllers\Admin;

use File;
use App\Model\Book;
use App\Model\Chapter;
use App\Model\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SectionController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $chapter = Chapter::find($request->get('chapter_id'));
        $book    = $chapter ? $chapter->book : Book::find($request->get('book_id'));
        if ($request->has('book_id') && $book->type == Book::TYPE_CHAPTER) {
            return redirect('chapter?book_id=' . $book->id);
        }
        $data = $chapter ? $chapter->sections() : $book->sections();

        return view('admin.section.index', [
            'book'    => $book,
            'chapter' => $chapter,
            'data'    => $data->orderBy('order')->get(),
        ]);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        $section = Section::find($id);
        $path    = mb_convert_encoding($section->path, 'gb2312', "utf-8");
        if (File::exists($path)) {
            $txt = File::get($path);
        } else {
            $txt = '未找到文件';
        }

        return view('admin.section.edit', [
            'item' => $section,
            'txt'  => $txt
        ]);
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $section = Section::find($id);
        $section->update($request->all());
        if ($section->chapter_id) {
            return redirect("section?chapter_id=$section->chapter_id");
        }

        return redirect("section?book_id=$section->book_id");
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        $section    = Section::find($id);
        $book_id    = $section->book_id;
        $chapter_id = $section->chapter_id;
        $section->delete();

        return redirect($chapter_id ? "section?chapter_id=$chapter_id" : "section?book_id=$book_id");
    }
}
