<?php

/**
 * gearman练习
 * @author Bear
 * @version 1.0
 * @copyright http://maimengmei.com
 * @created 2014-10-16 14:41
 */
class GearmanController extends Controller
{
    /**
     * gearman client
     */
    public function actionClient() {
        $client = new GearmanClient();
        $client->addServer('192.168.17.130', 4730);
        print_r($client->doNormal('title', 'Linve'));
    }
    
}
