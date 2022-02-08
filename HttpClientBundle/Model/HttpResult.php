<?php

namespace MJYDH\HttpClientBundle\Model;

class HttpResult{
    public $http_code;
    public $body;
    public $header;
    public $response;

    public function __construct()
    {
        $this->setHttpCode("0");
    }
    /**
     * Setea el http code
     * 
     * @param string $http_code
     */
    public function setHttpCode( $http_code){
        $this->http_code = $http_code;
    }

    /**
     * Retorna el http code de la respuesta
     * 
     * @return string
     */
    public function getHttpCode()   {
        return $this->http_code;
    }

    /**
     * Setea el body de la respuesta
     * 
     * @param string $body
     */
    public function setBody( $body){
        $this->body = $body;
    }

    /**
     * Retorna el body de la respuesta
     * 
     * @return string
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * Setea el header de la respuesta
     * 
     * @param string $header
     */
    public function setHeader( $header){
        $this->header = $header;
    }

    /**
     * Retorna el header de la respuesta
     * 
     * @return string
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * Setea el response de la respuesta (es el reponse como lo devuelve el curl)
     * 
     * @param string $response
     */
    public function setResponse($response){
        $this->response = $response;
    }

    /**
     * Retorna el response de la respuesta (es el reponse como lo devuelve el curl)
     * 
     * @return string
     */
    public function getResponse(){
        return $this->response;
    }
}