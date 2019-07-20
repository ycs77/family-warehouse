<?php

namespace App\Console\Commands;

use App\Item;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Intervention\Image\ImageManager;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vinkla\Hashids\HashidsManager;

class ItemsImageGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warehouse:items-img:a4
                            {itemsid : The items id, use "," delimited, use "-" designation range}
                            {--force : Force the operation to make QR code image}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make the items A4 image to print';

    /**
     * @var \Intervention\Image\ImageManager
     */
    protected $image;

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $disk;

    /**
     * @var \Vinkla\Hashids\HashidsManager
     */
    protected $hashids;

    public function __construct(ImageManager $image, FilesystemManager $files, HashidsManager $hashids)
    {
        parent::__construct();

        $this->image = $image;
        $this->disk = $files->disk('local');
        $this->hashids = $hashids;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $items = Item::whereIn('id', $this->getItemsId($this->argument('itemsid')))->get();

        if ($items->isEmpty()) {
            $this->error('The items is not found!');
            return false;
        }

        $width = $this->getHdDpiPx(595);
        $height = $this->getHdDpiPx(842);
        $x = $this->getHdDpiPx(43);
        $y = $this->getHdDpiPx(45);
        $box_width = $this->getHdDpiPx(85);
        $box_height = $this->getHdDpiPx(109);
        $columns_num = 6;
        $rows_num = 7;
        $per_page = $columns_num * $rows_num;
        $page_total = (int)ceil($items->count() / $per_page);
        $line_text_num = 8;

        // Pagination
        foreach (range(1, $page_total) as $page) {
            // White background
            $image = $this->image->canvas($width, $height, '#ffffff');

            // For this page's QR code
            foreach ($items->forPage($page, $per_page) as $i => $item) {
                $offset_x = $i % $columns_num;
                $offset_y = (int)floor($i / $columns_num);

                // Item QR code
                $itemQrcode = $this->image->make($this->getQrcode($item->id, $box_width));
                $image->insert(
                    $itemQrcode,
                    'top-left',
                    $x + $box_width * $offset_x,
                    $y + $box_height * $offset_y
                );

                // Rectangle border
                $image->rectangle(
                    $x + $box_width * $offset_x,
                    $y + $box_height * $offset_y,
                    $x + $box_width * ($offset_x + 1),
                    $y + $box_height * ($offset_y + 1),
                    function ($draw) {
                        $draw->background('rgba(0, 0, 0, 0)');
                        $draw->border(2, '#eeeeee');
                    }
                );

                // Texts
                $lines = explode("\n", wordwrap($item->name, $line_text_num * strlen('文'), "\n", true));
                $text_y = $y + $box_width + 8 + $box_height * $offset_y;
                foreach ($lines as $line) {
                    $image->text($line, $x + $box_width * $offset_x + $box_width / 2, $text_y, function ($font) {
                        $font->file(resource_path('fonts/msjh.ttc'));
                        $font->size(24);
                        $font->align('center');
                        $font->valign('top');
                    });
                    $text_y += 30;
                }
            }

            if (!$this->disk->exists('print_a4')) {
                $this->disk->makeDirectory('print_a4');
            }

            $imgName = now()->format('Y-m-d-H-i-s') . ($page_total > 1 ? "-$page" : '');
            $image->save(storage_path("app/print_a4/$imgName.jpg"));
        }

        $this->info('Successfully make the items A4 image.');
    }

    protected function getItemsId(string $ids)
    {
        $items = [];
        $ids = str_replace(' ', '', $ids);

        foreach (explode(',', $ids) as $group) {
            if (preg_match('/\d-\d/', $group)) {
                $range = explode('-', $group);
                foreach (range((int)$range[0], (int)$range[1]) as $number) {
                    $items[] = $number;
                }
            } else {
                $items[] = (int)$group;
            }
        }

        return $items;
    }

    public function getHdDpiPx($px): int
    {
        return (int)($px / 72 * 300);
    }

    protected function getQrcode($data, $size)
    {
        $url = "print_a4_qrcode/$data.png";

        if (!$this->disk->exists($url) || $this->option('force')) {
            $img = QrCode::format('png')->size($size)->generate($this->encodeData($data));
            $this->disk->put($url, $img);
        }

        return storage_path("app/$url");
    }

    protected function encodeData($data)
    {
        return $this->hashids->encode($data);
    }
}
