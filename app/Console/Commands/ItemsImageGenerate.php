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
                            {itemsid : The items id, use "," delimited, use "-" designation range}';

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

        $x = 14;
        $y = 27;
        $boxWidth = 236;
        $boxHeight = 304;
        $perPage = 110;
        $total = (int)ceil($items->count() / $perPage);

        $borderImg = $this->image->make(resource_path('images/print_a4/border.png'));

        foreach (range(1, $total) as $page) {
            $bgImg = $this->image->make(resource_path('images/print_a4/white.jpg'));

            foreach ($items->forPage($total, $perPage) as $i => $item) {
                $offset_x = $i % 10;
                $offset_y = (int)floor($i / 10);

                // Item QR code
                $itemQrcode = $this->image->make($this->getQrcode($item->id, $boxWidth));
                $bgImg->insert($itemQrcode, 'top-left', $x + $boxWidth * $offset_x, $y + $boxHeight * $offset_y);

                // Border image
                $bgImg->insert($borderImg, 'top-left', $x + $boxWidth * $offset_x, $y + $boxHeight * $offset_y);

                // Texts
                $lines = explode("\n", wordwrap($item->name, 8 * strlen('文'), "\n", true));
                $text_y = $y + $boxWidth + 4 + $boxHeight * $offset_y;
                foreach ($lines as $line) {
                    $bgImg->text($line, $x + $boxWidth * $offset_x + $boxWidth / 2, $text_y, function ($font) {
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

            $imgName = now()->format('Y-m-d-H-i-s') . ($total > 1 ? "-$page" : '');
            $bgImg->save(storage_path("app/print_a4/$imgName.jpg"));
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

    protected function getQrcode($data, $size)
    {
        $url = "print_a4_qrcode/$data.png";

        if (!$this->disk->exists($url)) {
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
