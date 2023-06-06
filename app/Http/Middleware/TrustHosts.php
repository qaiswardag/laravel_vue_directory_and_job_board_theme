<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts()
    {
        return [$this->allSubdomainsOfApplicationUrl()];
    }

    /**
     * Get all of the application URL's subdomains.
     *
     * @return array<int, string|null>
     */
    protected function allSubdomainsOfApplicationUrl()
    {
        return ["https://myself.myissue.io", "https://www.myself.myissue.io"];
    }
}
