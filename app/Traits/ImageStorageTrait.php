<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageStorageTrait {

    /**
     * Saves an image to cloud storage with the option to remove the old image.
     * 
     * @param UploadedFile   $image
     * @param Boolean        $removeOld
     * @param String         $oldImage
     * @return String
     */
    public function saveImageToStorage($image, $removeOld = false, $oldImage = '', $imageFolder = '/awfImages/', $filesystem = 'local') {
        $url = '';
        if ($image->isValid()) {
            $disk = Storage::disk($filesystem);
            if ($removeOld && !empty($oldImage)) {
                
                $disk->delete($this->getStorageImageName($oldImage, $imageFolder));
            }
            $imageName = $disk->put('', $image, 'public');
            $url = $disk->url($imageName);
        }

        return $url;
    }

    /**
     * Gets the file name of the image with the provided name in cloud storage.
     * 
     * @param String $imageName
     * @return String
     */
    public function getStorageImageName($imageName, $imageFolder) {
        if (str_contains($imageName, $imageFolder)) {
            return substr($imageName, strpos($imageName, $imageFolder) + strlen($imageFolder));
        }

        return $imageName;
    }
}