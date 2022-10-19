<?php

namespace MJYDH\HttpClientBundle\Exception;

use MJYDH\HttpClientBundle\Model\HttpResult;

/**
 * Description of ContexException
 *
 * @author Usuario
 */
class HttpException extends \Exception {

    private $httpResult = null;
    private $title = null;

    /**
     * Constructor
     * @param string $title Titulo de la excepción lanzada
     * @param string $message Descripcion del la excepción lanzada
     * @param HttpResult response Respues de la peticion http
     */
    public function __construct(string $title, string $message, HttpResult $httpResult = null) {
        $this->title = $title;
        $this->httpResult = $httpResult;
        parent::__construct($message, 0);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getHttpResult() {
        return $this->httpResult;
    }

    public function getTitle() {
        return $this->title;
    }

}
