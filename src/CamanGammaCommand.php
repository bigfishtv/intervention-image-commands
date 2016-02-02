<?php

namespace Intervention\Image\Imagick\Commands;

class CamanGammaCommand extends \Intervention\Image\Commands\AbstractCommand
{	
	public function execute($image)
    {	
        $gamma = $this->argument(0)->value();
        $gamma = pow($gamma, -1); // CamanJS equivalent
        return $image->gamma($gamma);
    }
}