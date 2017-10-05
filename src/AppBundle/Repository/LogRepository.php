<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 05/10/2017
 * Time: 09:30
 */

namespace AppBundle\Repository;


class LogRepository extends BaseRepository
{
    public function __construct($manager, $classMetadata)
    {
        parent::__construct($manager, $classMetadata, 'l');
    }
}