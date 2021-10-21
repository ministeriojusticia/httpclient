<?php

namespace MJYDH\HttpClientBundle\Service;

use MJYDH\HttpClientBundle\Exception\HttpException;


class HttpClient {

    var $ERROR_TITLE = "HttpClient Error";
    var $url = null;
    var $user = null;
    var $password = null;
    var $httpCodeResponses = null;
    var $catchExceptions = null;

    public function setURL($url) { $this->url = $url; }

    private function getURL()
    {
        if ($this->url === null) { throw new HttpException("Rest Service", "URL request no definida!"); }

        return $this->url;
    }
    
    public function setAuth($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }
    
    private function getUser() { return $this->user; }
    
    private function getPassWord() { return $this->password; }

    /**
     * Setea los http code que debe responder el execute.
     * Los http code que no esten en las lista, los maneja el execute automaticamente.
     * 
     * @param $codesArray array("http_code")
     */
    public function setHttpCodeResponses($codesArray){
        $this->httpCodeResponses = $codesArray;
    }

    public function getHttpCodeResponses(){
        return $this->httpCodeResponses;
    }

    /**
     * Setea los http codes y sus respectivos mensajes de error que debe capturarse
     * 
     * @param $catchExceptions array("http_code" => "Mensaje de error")
     */
    public function setCatchExceptions($catchExceptions){
        $this->catchExceptions = $catchExceptions;
    }

    public function getCatchExceptions(){
        return $this->catchExceptions;
    }

    /**
     * Ejecuta el http configurado.
     * Si no tiene configurado los CatchExceptions y HttpCodeResponses
     * para todas los response >= a 400 tira una HttpException
     *                                                          title: "HttpClient Error"
     *                                                          message: http_code
     *                                                          data: body de la petición
     *                                                          code = 0
     *
     */
    public function Execute($method = 'GET', $url = null, $params = null)
    {
        try
        {
            ($url === null) ? $api_request_url = $this->getURL() : $api_request_url = $url;

            if (!in_array($method, array("PATCH","DELETE", "GET", "POST", "PUT")))
                { throw new HttpException($this->ERROR_TITLE, "Metodo no válido!", $method); }
            
            $method_name = $method;
            
            // Let's set all Request Parameters (api_key, token, user_id, etc)
            $api_request_parameters = array();

            if ($params !== null)
            {
                if (is_array($params))
                    { $api_request_parameters = array_merge($api_request_parameters, $params); }
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            if ($method_name == 'DELETE')
            {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($api_request_parameters));
            }

            if ($method_name == 'GET')
                { $api_request_url .= '?' . http_build_query($api_request_parameters); }

            if ($method_name == 'POST')
            {
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($api_request_parameters));
            }

            if ($method_name == 'PUT')
            {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($api_request_parameters));
            }

            // Here you can set the Response Content Type you prefer to get: application/json, application/xml,
            // text/html, text/plain, etc
            //
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

            // Let's give the Request Url to Curl
            //
            curl_setopt($ch, CURLOPT_URL, $api_request_url);

            // Yes we want to get the Response Header (it will be mixed with the response body but we'll separate that
            // after)
            //
            curl_setopt($ch, CURLOPT_HEADER, TRUE);

            // Allows Curl to connect to an API server through HTTPS
            //
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            // Si tiene usuario es BASIC AUTH
            //
            if ($this->getUser() !== null)
            {
                $user = $this->getUser();
                $pass = $this->getPassWord();

                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
            }

            // Let's get the Response!
            //
            $api_response = curl_exec($ch);

            // We need to get Curl infos for the header_size and the http_code
            //
            $api_response_info = curl_getinfo($ch);

            // Don't forget to close Curl
            //
            curl_close($ch);

            $http_code = $api_response_info['http_code'];

            if ($this->getHttpCodeResponses() != null){
                if (in_array($http_code,$this->getHttpCodeResponses())){
                    return $api_response;
                }
            }

            $httpCatchEx = $this->getCatchExceptions();
            if (isset($httpCatchEx[$http_code])){
                $catchEx = $httpCatchEx[$http_code];
                
                throw new HttpException(($catchEx->getTitle() ? $catchEx->getTitle() : $this->ERROR_TITLE), $httpCatchEx[$http_code]->getMessage(), $api_response);
            }

            if ($http_code >= 400)
            {
                { throw new HttpException($this->ERROR_TITLE, $http_code, $api_response); }
            }
            
            return $api_response;

        } catch (HttpException $rex) {
            throw new HttpException($rex->getTitle(), $rex->getMessage(), $rex->getData() );
        } catch (\Exception $ex) {
            throw new HttpException($ex->getCode(), $ex->getMessage());
        }
    }
}

class CatchExceptions{
    public $title = null;
    public $message = null;

    public function __construct($message, $title = null){
        $this->message = $message;
        $this->title = $title;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setMessage($message){
        $this->message = $message;
    }
    
    public function getMessage(){
        return $this->message;
    }
}
