<?php

namespace Intervention\Image\Imagick\Commands;

class CamanBlurCommand extends \Intervention\Image\Commands\AbstractCommand
{	
	public function execute($image)
    {	
        $blur = $this->argument(0)->value();
        return $image->blur($blur);
    }
}