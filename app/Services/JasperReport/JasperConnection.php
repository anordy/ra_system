<?php

namespace App\Services\JasperReport;

use Jaspersoft\Client\Client;

class JasperConnection
{

    /**
     * @return Client
     * create connection to jasper server here
     */
    public static function getConnectionInstance(): Client
    {
        return new Client(
            config('modulesconfig.jasper.JSP_URL'),
            config('modulesconfig.jasper.JSP_USER'),
            config('modulesconfig.jasper.JSP_PASSWORD'),
        );
    }
}
