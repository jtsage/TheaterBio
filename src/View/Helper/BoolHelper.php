<?php
/* src/View/Helper/BoolHelper.php (using other helpers) */

namespace App\View\Helper;

use Cake\View\Helper;

class BoolHelper extends Helper
{

    public function prefNo($value)
    {
        return (($value) ? "<strong>YES</strong>" : "no");
    }
    public function prefYes($value)
    {
        return ((!$value) ? "<strong>NO</strong>" : "yes");
    }
}
?>