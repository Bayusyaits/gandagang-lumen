<?php

function isActiveUser($data = []) {
    
    if(isset($data) && !empty($data)){
        
        $user = model('Users')::activeuser($data)->first();
        return $user;
    
    } else {
      
        return '';
    
    }
}

function getRequestHeaders() {
    $headers = array();
    foreach($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

function getUrlApi() {

	if(env('APP_ENV', 'production')){
        $url = 'https://api.bayusyaits.com/api/';
    }else {
        $url = 'https://bayusyaits.laravel.com/api/';
    }

    return $url;
}
//use validate header and form for request api.
function clientRequest($data = []) {
    return 'halo';
}

function getClientQueryApi($data = []) {

    $body = [];

    if(isset($data) && !empty($data['form_params'])){
        $form_params = $data['form_params'];
    }else {
        $form_params = '';
    }

    if(isset($data) && !empty($data['body'])){
        $body = $data['body'];
    }else {
        $body = '';
    }
    
    if(isset($data) && isset($data['secret_key']) && $data['secret_key'] !== 0) {
        $secret = explode('||secret||', $data['secret_key']);
        if(isset($secret) && isset($secret[0]) && isset($secret[1]) && isset($secret[2])) {
            $secret_key = $secret[0];
            $username   = $secret[1];
            $password   = $secret[2];
        }else {
            $secret_key = '';
            $username   = '';
            $password   = '';
        }
    }else {
        $secret_key = '';
        $username   = '';
        $password   = '';
    }

    if(isset($data) && !empty($data)) {
        $query['body']          = $body;
        $query['operation']     = $body['operation'];
        $query['role']          = isset($body['role']) && !empty($body['role']) ? $body['role'] : '';
        $query['lang']          = isset($body['lang']) && !empty($body['lang']) ? $body['lang'] : 'en';
        $query['keyword']       = isset($body['keyword']) && !empty($body['keyword']) ? $body['keyword'] : '';
        $query['hostname']      = $data['hostname'];
        $query['client_secret'] = $secret_key;
        $query['body']['ip']    = $data['ip'];
        $query['username']      = $username;
        $query['password']      = $password;

        return $query;

    }else {
        return '';
    }

}

function getAccessClientApi($data = '') {
    $user = [];

    if(isset($data) && !empty($data)) {

        $user['id']             = 2;
        $user['name']           = 'bayusyaits';
        $user['email']          = 'bayusyaits@gmail.com';

        return $user;
    }else {
        return '';
    }

}

function getClientHeadersApi($data = []) {

    if(isset($data) && !empty($data)) {
        $array = [];
        $array['headers'] = 
                        array(
                        'User-Agent'        => 'testing/1.0',
                        'Accept'            => 'application/json',
                        'Content-type'      => 'application/json',
                        'secret_key'        => "Bvh0iuoIyYlJgMho3imzDaTBczTEL6MagTwdtGB5",
                        'client'            => $data['hostname'],
                        'X-Foo'             => ['Bar', 'Baz']
                        );
    
        $array['connect_timeout'] = 6;
        return $array;
    }else {
        return '';
    }

}