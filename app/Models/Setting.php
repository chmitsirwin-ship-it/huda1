<?php

namespace App\Models;

use ElipZis\Cacheable\Models\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;

class Setting extends \Outerweb\Settings\Models\Setting
{
    use Cacheable;
}
