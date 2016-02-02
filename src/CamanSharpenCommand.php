<?php

namespace Intervention\Image\Imagick\Commands;

class CamanSharpenCommand extends \Intervention\Image\Commands\AbstractCommand
{	
	public function execute($image)
    {	
        $sharpen = $this->argument(0)->value();
        $sharpen = $sharpen / 4;
        return $image->sharpen($sharpen);
    }
}