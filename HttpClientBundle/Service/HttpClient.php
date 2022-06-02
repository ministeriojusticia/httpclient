<?php

namespace MJYDH\HttpClientBundle\Service;

use MJYDH\HttpClientBundle\Exception\HttpException;
use MJYDH\HttpClientBundle\Model\HttpResult;
use MJYDH\HttpClientBundle\Exception\CatchExceptions;


class HttpClient {

    var $ERROR_TITLE = "HttpClient Error";
    var $url = null;
    var $user = null;
    var $password = null;
    var $apikey = null;
    var $apivalue = null;
    var $httpCodeResponses = null;
    var $catchExceptions = null;

    /**
     * Setea el title por default que va a tener el HttpException 
     * 
     * @param string $error mensage de error
     */
    public function setDefatulErrorTitle($error){
        $this->ERROR_TITLE = $error;
    }

    /**
     * Setea la URL de llamada
     * 
     * @param string $url url
     */
    public function setURL($url) { $this->url = $url; }

    /**
     * Retorna la url 
     * 
     * @return string
     */
    private function getURL()
    {
        if ($this->url === null) { throw new HttpException("Rest Service", "URL request no definida!"); }

        return $this->url;
    }

    /**
     * Setea el usuraio y contraseña para basic auth
     * 
     * @param string $user usuario 
     * @param string $password contraseña
     */
    public function setAuth($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }
    
    /**
     * Retorna el usuario 
     * 
     * @return string
     */
    private function getUser() { return $this->user; }
    
    public function setHeaderApiKey(string $value, string $key = "apikey"){
        $this->apivalue = $value;
        $this->apikey = $key;
    }

    public function getHeaderApiKey(){
        return $this->apikey;
    }

    public function getHeaderApiValue(){
        return $this->apivalue;
    }

    /**
     * Retorna la contraseña
     * 
     * @return string
     */
    private function getPassWord() { return $this->password; }

    /**
     * Setea los http code que debe responder el execute.
     * Los http code que no esten en las lista, los maneja el execute automaticamente.
     * 
     * @param array("http_code") $codesArray
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
     * @param array("http_code" => CatchExceptions) $catchExceptions 
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
     * @param string $method optional default 'GET'
     * @param string $url optional 
     * @param array $params optional 
     * 
     * @return HttpResult
     */
    public function Execute(string $method = 'GET', string $url = null, array $params = null) : HttpResult
    {
        try
        {
            $httpResult = new HttpResult();

            ($url === null) ? $api_request_url = $this->getURL() : $api_request_url = $url;

            if (!in_array($method, array("PATCH","DELETE", "GET", "POST", "PUT")))
                { throw new HttpException($this->ERROR_TITLE, "Metodo no válido!"); }
            
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

            if ($this->getHeaderApiKey() !== null){
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    $this->getHeaderApiKey().':' . $this->getHeaderApiValue()
                    ));
            }

            // Let's get the Response!
            //
            $api_response = curl_exec($ch);

            // We need to get Curl infos for the header_size and the http_code
            //
            $api_response_info = curl_getinfo($ch);

            curl_close($ch);

            /*
            Here we separate the Response Header from the Response Body
            */
            $http_code = $api_response_info['http_code'];

            $httpResult->setHttpCode($http_code);
            $httpResult->setHeader(trim(substr($api_response, 0, $api_response_info['header_size'])));
            $httpResult->setBody(substr($api_response, $api_response_info['header_size']));
            $httpResult->setResponse($api_response);

            if ($this->getHttpCodeResponses() != null){
                if (in_array($http_code,$this->getHttpCodeResponses())){
                    return $httpResult;
                }
            }

            $httpCatchEx = $this->getCatchExceptions();
            if (isset($httpCatchEx[$http_code])){
                $catchEx = $httpCatchEx[$http_code];
                throw new CatchExceptions(
                                (is_null($catchEx->getTitle()) ? $this->ERROR_TITLE : $catchEx->getTitle()),
                                $httpCatchEx[$http_code]->getMessage(),
                                $httpResult);
            }
            
            if ($http_code >= 400 || $http_code < 100)
            {
                throw new HttpException($this->ERROR_TITLE, $http_code, $httpResult);
            }
            return $httpResult;

        } catch (HttpException $rex) {            
            throw new HttpException($rex->getTitle(), $rex->getMessage(), $httpResult);
        } catch (CatchExceptions $rex) {
            throw new CatchExceptions($rex->getTitle(), $rex->getMessage(), $httpResult);
        } catch (\Exception $ex) {
            throw new HttpException($ex->getCode(), $ex->getMessage(),$httpResult);
        }
    }

}