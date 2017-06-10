<?php
namespace App\Console\Commands\Book;

use App\Model\Chapter;
use DB;
use File;
use App\Model\Book;
use App\Model\Section;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;

class ScanSection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:section';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scan section';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('chapters')->truncate();
        DB::table('sections')->truncate();
        Book::whereNotNull('path')->orderBy('id')->chunk(20, function ($books) {
            $bookData = [];
            foreach ($books as $book) {
                foreach (File::allFiles($book->path) as $file) {
                    $name                            = str_replace("$book->path/", '', $file);
                    $path                            = explode('/', $name);
                    $chapter                         = $path[count($path) - 2];
                    $section                         = $path[count($path) - 1];
                    $bookData[$book->id][$chapter][] = [
                        'book_id' => $book->id,
                        'name'    => $section,
                        'path'    => $file,
                    ];
                }
            }
            $sectionData  = [];//章节
            $sectionDataN = [];//节
            foreach ($bookData as $bk => $bv) {
                if (1 == count($bv)) {
                    $sOrder = 1;//节排序
                    Book::find($bk)->update(['type' => Book::TYPE_SECTION]);
                    foreach (array_values($bv)[0] as $section) {
                        $section['order'] = $sOrder++;
                        $sectionDataN[]   = $section;
                    }
                } else {
                    Book::find($bk)->update(['type' => Book::TYPE_CHAPTER]);
                    $cOrder = 1;//章排序
                    foreach ($bv as $ck => $cv) {
                        $cm     = Chapter::create(['book_id' => $bk, 'name' => $ck, 'order' => $cOrder++]);
                        $sOrder = 1;//节排序
                        foreach ($cv as $section) {
                            $section['order']      = $sOrder++;
                            $section['chapter_id'] = $cm->id;
                            $sectionData[]         = $section;
                        }
                    }
                }
            }
            Section::insert($sectionDataN);
            Section::insert($sectionData);
        });
    }
}
