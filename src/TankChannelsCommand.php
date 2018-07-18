<?php

namespace Intervention\Image\Imagick\Commands;

class TankChannelsCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $img = $image->getCore();
        $channels = $this->argument(0)->value();
        
        foreach ($channels as $key => &$value) {
            $value = $value / 100;
        }

        $r = isset($channels['red']) ? $channels['red'] : 0;
        $g = isset($channels['green']) ? $channels['green'] : 0;
        $b = isset($channels['blue']) ? $channels['blue'] : 0;

        if (method_exists($img, 'colorMatrixImage')) {
            $img->colorMatrixImage([
                1, 0, 0, 0, $r,
                0, 1, 0, 0, $g,
                0, 0, 1, 0, $b,
                0, 0, 0, 1, 0,
                0, 0, 0, 0, 1,
            ]);
        } else {
            $img->recolorImage([
                1, 0, 0, $r,
                0, 1, 0, $g,
                0, 0, 1, $b,
                0, 0, 0, 1,
            ]);
        }

        return $image;
    }
}
