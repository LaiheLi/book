<?php
namespace App\Console\Commands\Book;

use Excel;
use App\Model\Book;
use Illuminate\Console\Command;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import book excel';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Excel::filter('chunk')->selectSheetsByIndex(0)->load('storage/app/book.xls')->chunk(1000, function ($results) {
            Book::insert($results->toArray());
        });
    }
}
