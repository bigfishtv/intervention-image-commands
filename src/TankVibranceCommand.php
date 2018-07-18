<?php

namespace Intervention\Image\Imagick\Commands;

class TankVibranceCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $img = $image->getCore();
        $vibrance = $this->argument(0)->value() ?: 0;

        $amt = $vibrance / 200;
        $v1 = $amt + 1;
        $v2 = -$amt / 1.5;

        if (method_exists($img, 'colorMatrixImage')) {
            $img->colorMatrixImage([
                $v1, $v2, $v2, 0,   0,
                $v2, $v1, $v2, 0,   0,
                $v2, $v2, $v1, 0,   0,
                $v2, $v2, $v2, $v1, 0,
                0,   0,   0,   0,   1,
            ]);
        } else {
            $img->recolorImage([
                $v1, $v2, $v2, 0,
                $v2, $v1, $v2, 0,
                $v2, $v2, $v1, 0,
                $v2, $v2, $v2, $v1,
            ]);
        }

        return $image;
    }
}
