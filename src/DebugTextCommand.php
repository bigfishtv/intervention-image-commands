<?php

namespace Intervention\Image\Imagick\Commands;

use \ImagickDraw;

class DebugTextCommand extends \Intervention\Image\Commands\AbstractCommand
{	
	public function execute($image)
    {	
        $text = $this->argument(0)->value();

		$draw = new ImagickDraw();
	    $draw->setStrokeColor('black');
	    $draw->setFillColor('white');
		$draw->setStrokeWidth(2);
		$draw->setFontSize(28);
		$draw->setFont(dirname(dirname(__FILE__)) . '/Lato-Black.ttf');

		$img = $image->getCore();
		$img->annotateImage($draw, 40, 40, 0, $text);

        return $img;
    }
}