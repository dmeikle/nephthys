<?php
/*
 *  This file is part of the Quantum Unit Solutions development package.
 *
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/14/2017
 * Time: 12:24 AM
 */

namespace Gossamer\Nephthys\Rest;


class GenericRestExtension extends \RestClient
{
    public function execute($url, $method='GET', $parameters=[], $headers=[]){
        $client = clone $this;
        $client->url = $url;
        $client->handle = curl_init();
        $curlopt = [
            CURLOPT_HEADER => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_USERAGENT => $client->options['user_agent']
        ];

        if($client->options['username'] && $client->options['password'])
            $curlopt[CURLOPT_USERPWD] = sprintf("%s:%s",
                $client->options['username'], $client->options['password']);

        if(count($client->options['headers']) || count($headers)){
            $curlopt[CURLOPT_HTTPHEADER] = [];
            $headers = array_merge($client->options['headers'], $headers);
            foreach($headers as $key => $values){
                foreach(is_array($values)? $values : [$values] as $value){
                    $curlopt[CURLOPT_HTTPHEADER][] = sprintf("%s:%s", $key, $value);
                }
            }
        }
//new update from base RestClient - breaks urls not expecting '.json' in the url request
//        if($client->options['format'])
//            $client->url .= '.'.$client->options['format'];
//        echo $client->url."\r\n";


        // Allow passing parameters as a pre-encoded string (or something that
        // allows casting to a string). Parameters passed as strings will not be
        // merged with parameters specified in the default options.


        if(is_array($parameters)){
            $parameters = array_merge($client->options['parameters'], $parameters);
            $parameters_string = $client->format_query($parameters);
        }
        else
            $parameters_string = (string) $parameters;

        if(strtoupper($method) == 'POST'){
            $curlopt[CURLOPT_POST] = TRUE;
            $curlopt[CURLOPT_POSTFIELDS] = $parameters_string;
        }
        elseif(strtoupper($method) != 'GET'){
            $curlopt[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
            $curlopt[CURLOPT_POSTFIELDS] = $parameters_string;
        }
        elseif($parameters_string){
            $client->url .= strpos($client->url, '?')? '&' : '?';
            $client->url .= $parameters_string;
        }

        if($client->options['base_url']){
            if($client->url[0] != '/' && substr($client->options['base_url'], -1) != '/')
                $client->url = '/' . $client->url;
            $client->url = $client->options['base_url'] . $client->url;
        }
        $curlopt[CURLOPT_URL] = $client->url;

        if($client->options['curl_options']){
            // array_merge would reset our numeric keys.
            foreach($client->options['curl_options'] as $key => $value){
                $curlopt[$key] = $value;
            }
        }
        curl_setopt_array($client->handle, $curlopt);
        pr($client->handle);
        $client->parse_response(curl_exec($client->handle));
        $client->info = (object) curl_getinfo($client->handle);
        $client->error = curl_error($client->handle);

        curl_close($client->handle);
        return $client;
    }
}