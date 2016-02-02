<?php

namespace Intervention\Image\Imagick\Commands;

class CamanSaturationCommand extends \Intervention\Image\Commands\AbstractCommand
{	
	public function execute($image)
    {	
        $saturation = $this->argument(0)->value();
        return $image->getCore()->modulateImage(100, 100+$saturation, 100);
    }
}