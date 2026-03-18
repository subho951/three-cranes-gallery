<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class ConvertProductImagesToWebp extends Command
{
    protected $signature = 'images:convert-webp';
    protected $description = 'Convert product images to webp under 50kb and update DB';

    public function handle()
    {
        $path = public_path('uploads/product');

        $images = File::files($path);

        $this->info('Total files found: ' . count($images));

        foreach ($images as $file) {

            $extension = strtolower($file->getExtension());

            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'])) {
                continue;
            }

            $oldName = $file->getFilename();
            $newName = pathinfo($oldName, PATHINFO_FILENAME) . '.webp';
            $newPath = $path . '/' . $newName;

            try {

                $manager = new ImageManager(new Driver());
                $img = $manager->read($file->getPathname());

                // Resize if huge (optional but helps compression)
                if ($img->width() > 2000) {
                    $img->scaleDown(2000);
                }

                // Try quality loop to get < 50KB
                $quality = 80;

                do {
                    $img->toWebp($quality)->save($newPath);
                    $size = filesize($newPath);
                    $quality -= 5;
                } while ($size > 70 * 1024 && $quality >= 30);

                // Delete old image
                File::delete($file->getPathname());

                // Update products table
                DB::table('products')
                    ->where('cover_image', $oldName)
                    ->update(['cover_image' => $newName]);

                // Update product_images table
                DB::table('product_images')
                    ->where('image', $oldName)
                    ->update(['image' => $newName]);

                $this->info("✔ Converted: $oldName → $newName (" . round($size / 1024, 1) . " KB)");
            } catch (\Exception $e) {
                $this->error("✖ Failed: $oldName | " . $e->getMessage());
            }
        }

        $this->info('✅ Image conversion completed');
    }
}
