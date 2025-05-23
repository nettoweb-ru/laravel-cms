<?php

namespace App\Listeners;

use Netto\Events\ModelDeleted;

class ModelDeletedListener
{
    /**
     * @param ModelDeleted $event
     * @return void
     */
    public function handle(ModelDeleted $event): void
    {

    }
}
