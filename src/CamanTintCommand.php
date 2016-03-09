<?php

namespace Intervention\Image\Imagick\Commands;
use \ImagickDraw;

class TintCommand extends \Intervention\Image\Commands\AbstractCommand
{
	public function execute($image)
	{
		$img = $image->getCore();
        $color = $this->argument(0)->value();
        $amount = $this->argument(1)->value() ?: 0.5;

		$draw = new ImagickDraw();
		$draw->setFillColor($color);

        list ($width, $height) = array_values($img->getImageGeometry());
        $draw->rectangle(0, 0, $width, $height);

        $img->drawImage($draw);
        return $image;
	}
}