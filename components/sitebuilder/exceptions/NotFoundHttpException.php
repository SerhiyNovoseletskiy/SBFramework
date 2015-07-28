<?php
/**
 * Created by PhpStorm.
 * User: serhiy
 * Date: 23.07.15
 * Time: 16:26
 */

namespace app\sitebuilder\exceptions;


class NotFoundHttpException extends HttpException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param integer $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(404, $message, $code, $previous);
    }
}