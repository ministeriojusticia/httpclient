<?php

namespace MJYDH\HttpClientBundle\Model;

class CatchExceptions{
    public $title = null;
    public $message = null;

    /**
     * Setea el mensaje y titulo del error. 
     * 
     * @param string $message Mensaje de error
     * @param string $title optional Titulo del error
     */
    public function __construct(string $message, string $title = null){
        $this->message = $message;
        $this->title = $title;
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

    /**
     * Setea el mensaje del error
     * 
     * @param string $message
     */
    public function setMessage(string $message){
        $this->message = $message;
    }
    
    /**
     * Retorna el mensaje de error
     * 
     * @return string
     */
    public function getMessage(): string{
        return $this->message;
    }
}
