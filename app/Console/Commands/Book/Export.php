<?php
namespace App\Console\Commands\Book;

use DB;
use App;
use File;
use Excel;
use App\Model\Book;
use Illuminate\Console\Command;

class Export extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'export books';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $i      = 1;
            $config = config('book');
            $books  = Book::where(['handle' => TRUE, 'export' => FALSE])->orderBy('catalog')->get();
            if (count($books) == 0) {
                return;
            }
            $bookData    = [];
            $sectionData = [];
            File::makeDirectory($this->encode($config['export'] . '/封面/'), 755, TRUE, TRUE);
            foreach ($books as $book) {
                echo "book = $book->id";
                $cover = $this->encode($config['image_path'] . '/' . $book->cover);
                //没有封面，不处理
                if (!File::exists($cover)) {
                    continue;
                }
                foreach ($book->sections as $section) {
                    if (!File::exists($this->encode($section->path))) {
                        continue 2;
                    }
                }
                File::copy($cover, $this->encode($config['export'] . '/封面/' . $book->cover));
                File::makeDirectory($this->encode($config['export'] . "/$book->catalog/$book->name/"), 755, TRUE, TRUE);
                //书籍数据
                $bookData[$book->id] = [
                    'id'               => $book->id,
                    'tosortid'         => NULL,
                    '书名'               => $book->name,
                    '类别'               => $book->catalog,
                    '封面'               => "/resource_library/电子书/封面/$book->cover",
                    'author'           => $book->author,
                    'origin'           => NULL,
                    'copyright'        => NULL,
                    'url'              => NULL,
                    'size'             => NULL,
                    'description'      => $book->description,
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
                //处理txt文件
                if ($book->type == Book::TYPE_CHAPTER) {
                    foreach ($book->chapters as $chapter) {
                        File::makeDirectory(
                            $this->encode(
                                $config['export'] . "/$book->catalog/$book->name/$chapter->name/"
                            ), 755, TRUE, TRUE
                        );
                        foreach ($chapter->sections as $section) {
                            $txt     = $this->encode($section->path);
                            $txtName = str_replace('.txt', '', $section->name) . '.txt';
                            File::copy($txt,
                                $this->encode(
                                    $config['export'] . "/$book->catalog/$book->name/$chapter->name/$txtName"
                                ));
                        }
                        $sectionData[$i++] = [
                            'id'                  => $chapter->id,
                            'tosortid'            => NULL,
                            '单节'                  => $chapter->name,
                            'filepath'            => NULL,
                            'filename'            => NULL,
                            'filename_extensions' => NULL,
                            '内容'                  => $this->formatData([
                                'name' => $chapter->name,
                                'data' => $chapter->sections->map(function ($item) use ($book, $chapter) {
                                    $txtName = str_replace('.txt', '', $item->name) . '.txt';

                                    return [
                                        'name' => $item->name,
                                        'url'  => "/resource_library/电子书/$book->catalog/$book->name/$chapter->name/$txtName",
                                    ];
                                })->toArray()
                            ]),
                            'books_id'            => NULL,
                            '书名'                  => $book->name,
                            'm_createdate'        => NULL,
                            'm_createdatetime'    => NULL,
                            'm_updatedate'        => NULL,
                            'm_updatedatetime'    => NULL
                        ];
                    }
                } else {
                    foreach ($book->sections as $section) {
                        $txt     = $this->encode($section->path);
                        $txtName = str_replace('.txt', '', $section->name) . '.txt';
                        File::copy($txt, $this->encode($config['export'] . "/$book->catalog/$book->name/$txtName"));
                        $sectionData[$i++] = [
                            'id'                  => $section->id,
                            'tosortid'            => NULL,
                            '单节'                  => $section->name,
                            'filepath'            => NULL,
                            'filename'            => NULL,
                            'filename_extensions' => NULL,
                            '内容'                  => $this->formatData([
                                'name' => $section->name,
                                'data' => [
                                    [
                                        'name' => $section->name,
                                        'url'  => "/resource_library/电子书/$book->catalog/$book->name/$txtName",
                                    ]
                                ]
                            ]),
                            'books_id'            => NULL,
                            '书名'                  => $book->name,
                            'm_createdate'        => NULL,
                            'm_createdatetime'    => NULL,
                            'm_updatedate'        => NULL,
                            'm_updatedatetime'    => NULL
                        ];
                    }
                }
                $book->export = TRUE;
                $book->save();
            }
            Excel::create('电子书目录', function ($excel) use ($bookData) {
                $excel->sheet('Sheet1', function ($sheet) use ($bookData) {
                    $sheet->setWidth(array(
                        'A' => 10,
                        'C' => 40,
                        'D' => 10,
                        'E' => 60,
                        'F' => 30,
                        'K' => 100
                    ));
                    $sheet->fromArray($bookData);
                });
            })->store('xlsx', $config['export']);
            Excel::create('电子书内容', function ($excel) use ($sectionData) {
                $excel->sheet('Sheet1', function ($sheet) use ($sectionData) {
                    $sheet->setWidth(array(
                        'A' => 10,
                        'C' => 100,
                        'G' => 40,
                        'I' => 40
                    ));
                    $sheet->fromArray($sectionData);
                });
            })->save('xlsx', $config['export']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getLine() . ':' . $e->getMessage() . PHP_EOL;
        }
    }

    private function encode($path)
    {
        return mb_convert_encoding(str_replace('/', '\\', $path), 'gb2312', "utf-8");
    }

    private function decode($path)
    {
        return mb_convert_encoding($path, "utf-8", 'gb2312');
    }

    private function formatData($data)
    {
        $str = "{name:\"$data[name]\",data:[";
        foreach ($data['data'] as $item) {
            $str .= "{name:\"$item[name]\",url:\"$item[url]\"},";
        }
        $str = substr($str, 0, -1);

        return $str . "]}";
    }
}
