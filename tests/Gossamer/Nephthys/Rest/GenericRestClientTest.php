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
 * Date: 3/9/2017
 * Time: 11:54 PM
 */

namespace tests\Gossamer\Nephthys\Rest;


use Gossamer\Nephthys\Rest\GenericRestClient;

class GenericRestClientTest extends \tests\BaseTest
{

    public function testValidConnection() {
        $conn = new GenericRestClient($this->getCredentials());

    }

    private function getCredentials() {
        return array(
            'datasource1' => array
            (
                'class' => 'Gossamer\Nephthys\Rest\GenericRestClient',
                'credentials' => array
                (
                    'baseUrl' => 'http://127.0.0.1:8060',
                    'format' => 'json',
                    'headers' => array
                    (
                        'serverName' => 'vancouver',
                        'serverAuth' => '$1$lIDKkGiyJVn2bZSQdxwEYW0'
                    )

                )

            )
        );
    }
}