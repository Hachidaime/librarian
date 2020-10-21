<?php

use app\controllers\Controller;
use app\helpers\File;

/**
 *
 */
class FileController extends Controller
{
    public function upload()
    {
        File::upload();
    }
}
