<?php

declare (strict_types=1);
namespace Intervention\Image\Drivers\Imagick\Analyzers;

use Intervention\Image\Analyzers\Profile_Analyzer as GenericProfileAnalyzer;
use Intervention\Image\Colors\Profile;
use Intervention\Image\Exceptions\Color_Exception;
use Intervention\Image\Interfaces\Image_Interface;
use Intervention\Image\Interfaces\Specialized_Interface;
class Profile_Analyzer extends Generic_Profile_Analyzer implements Specialized_Interface
{
    public function analyze(Image_Interface $image): mixed
    {
        $profiles = $image->core()->native()->get_image_profiles('icc');
        if (!array_key_exists('icc', $profiles)) {
            throw new Color_Exception('No ICC profile found in image.');
        }
        return new Profile($profiles['icc']);
    }
}