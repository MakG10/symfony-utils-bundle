<?php

namespace MakG\SymfonyUtilsBundle\Annotation;


use Doctrine\Common\Annotations\Annotation;

/**
 * When used on controller's action, it requires the presence of a valid CSRF token in order to process the request.
 *
 * @Annotation
 */
class CsrfTokenRequired extends Annotation
{
    /** @var string */
    public $id;

    /** @var string */
    public $header = 'X-CSRF-Token';

    /** @var string */
    public $param = 'token';
}
