<?php

namespace Intervention\Image\Imagick\Commands;

class TankBlurCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        // 0 - 100
        $blur = (int) max(0, min(255, $this->argument(0)->value()));

        return $image->blur($blur);
    }
}
