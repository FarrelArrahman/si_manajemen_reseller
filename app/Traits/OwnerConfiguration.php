<?php 

namespace App\Traits;

use App\Models\Configuration;

trait OwnerConfiguration {

    public function configuration()
    {
        return Configuration::first();
    }

}

?>