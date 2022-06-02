<?php

namespace MJYDH\HttpClientBundle\Exception;

use MJYDH\HttpClientBundle\Model\HttpResult;

class CatchExceptions  extends \Exception {
    private $httpResult = null;
    public $title = null;

    /**
     * Setea el mensaje y titulo del error. 
     * 
     * @param string $message Mensaje de error
     * @param string $title optional Titulo del error
     */
    public function __construct( string $title = null,string $message, HttpResult $httpResult = null) {
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

    /**
     * Setea el titulo del error
     * 
     * @param string $title
     */
    public function setTitle(string $title){
        $this->title = $title;
    }

    /**
     * Retorna el titulo del error
     * 
     * @return string
     */
    public function getTitle() : ?string{
        return $this->title;
    }
}