<?php
namespace App\Console\Commands\Book;

use DB;
use File;
use App\Model\Book;
use App\Model\Section;
use Illuminate\Support\Arr;
use Illuminate\Console\Command;

class ScanBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scan book';

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
        $root = config('book.root');
        $len  = strlen($root) + 1;
        foreach (File::directories($root) as $directory) {
            $directory = mb_convert_encoding($directory, "utf-8", 'gb2312');
            $name      = substr($directory, $len);
            if ($book = Book::where('name', $name)) {
                $book->update(['path' => $directory]);
            } else {
                \Log::error("$name  没有找到");
            }
        }
    }
}

             