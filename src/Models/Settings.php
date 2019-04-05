<?php

namespace OmarAliGit\Settings\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    
    protected $table = "settings";

    public function __construct()
    {
        $this->table = Config::get("laravel-settings.database.table");
    }



}
