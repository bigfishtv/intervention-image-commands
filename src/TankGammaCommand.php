<?php

namespace Intervention\Image\Imagick\Commands;

class TankGammaCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $gamma = $this->argument(0)->value();
        $gamma = pow($gamma, -1);

        return $image->gamma($gamma);
    }
}
