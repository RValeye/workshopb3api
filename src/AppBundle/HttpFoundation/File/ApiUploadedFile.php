<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 04/10/2017
 * Time: 16:47
 */

namespace AppBundle\HttpFoundation\File;

use Symfony\Component\HttpFoundation\File\File;

class ApiUploadedFile extends File
{
    public function __construct($base64Content)
    {
        $filePath = tempnam(sys_get_temp_dir(), 'UploadedFile');
        $file = fopen($filePath, 'w');
        stream_filter_append($file, 'convert.base64-decode');
        fwrite($file, $base64Content);
        $metadata = stream_get_meta_data($file);
        $path = $metadata['uri'];
        fclose($file);

        parent::__construct($path, true);
    }
}