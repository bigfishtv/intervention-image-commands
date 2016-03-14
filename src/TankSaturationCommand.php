<?php

namespace Intervention\Image\Imagick\Commands;

class TankSaturationCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $saturation = $this->argument(0)->value();
        $image->getCore()->modulateImage(100, 100 + $saturation, 100);

        return $image;
    }
}
