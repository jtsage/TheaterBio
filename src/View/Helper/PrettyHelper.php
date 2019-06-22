<?php
/* src/View/Helper/PrettyHelper.php (using other helpers) */

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Chronos\Chronos;

class PrettyHelper extends Helper
{
    public function next_run($original, $last, $period) {
        
        date_default_timezone_set(Configure::read('ServerTimeZoneFix'));
        $real_offset = date('Z');
        
        date_default_timezone_set('UTC');
        $now = Chronos::now();
        
        if ( $last->lt($original) ) { 
            return $original->i18nFormat(null, 'UTC');
        }
        $breaker = true;
        $ohshit = 0;
        $counter = $original->toMutable();
        while ( $breaker ) {
            $counter = $counter->modify("+" . $period . " days");
            if ( $counter->gte($now) ) { $breaker = false; }
            $ohshit++;
            if ( $ohshit > 1000 ) { "LoopError"; }
        }
        return $counter;
    }
    public function phone($value)
    {
        if ( $value  < 100000000 ) { return "n/a"; }
        return substr($value, 0, 3) . "." . substr($value, 3, 3) . "." . substr($value, 6, 4);
    }
    public function makeIcon($name, $icon, $text) {
        return "<span class='sr-only'>{$text}: {$name}</span><i class='fa fa-lg fa-fw fa-{$icon}' data-toggle='tooltip' data-placement='top' title='' data-original-title='{$text}: {$name}'></i></span>";
    }
    public function iconEdit($name)
    {
        return PrettyHelper::makeIcon($name, 'pencil-square-o', __('Edit'));
    }
    public function iconMark($name)
    {
        return PrettyHelper::makeIcon($name, 'check', __('Mark Paid'));
    }
    public function iconLock($name)
    {
        return PrettyHelper::makeIcon($name, 'lock', __('Change Password'));
    }
    public function iconView($name)
    {
        return PrettyHelper::makeIcon($name, 'eye', __('View'));
    }
    public function iconDelete($name)
    {
        return PrettyHelper::makeIcon($name, 'trash', __('Delete'));
    }
    public function iconAdd($name)
    {
        return PrettyHelper::makeIcon($name, 'plus', __('Add'));
    }
    public function iconPerm($name)
    {
        return PrettyHelper::makeIcon($name, 'cogs', __('User Permissions'));
    }
    public function iconDL($name)
    {
        return PrettyHelper::makeIcon($name, 'cloud-download', __('Download'));
    }
    public function iconUnpaid($name)
    {
        return PrettyHelper::makeIcon($name, 'usd', __('View Unpaid'));
    }
    public function helpButton($icon, $color = 'default', $name, $desc) {
        return '<a href="#" class="btn btn-' . $color . ' btn-sm"><i class="fa fa-fw fa-lg fa-' . $icon . '" aria-hidden="true"></i></a>' .
        ' <strong>' . $name . '</strong>: ' . $desc;
    }
    public function jqButton($icon, $color = 'default', $id, $class="", $title="") {
        return '<a href="#" title="' . $title . '" class="btn btn-' . $color . ' ' . $class . ' btn-sm" id="' . $id . '"><i class="fa fa-fw fa-lg fa-' . $icon . '" aria-hidden="true"></i></a>';
    }

    public function money($name, $label, $value=null) {
        $retty  = '<div class="form-group required">';
        $retty .= '<label class="control-label" for="' . $name . '">' . $label . '</label>';
        $retty .= '<div class="input-group"><div class="input-group-prepend"><div class="input-group-text">$</div></div>';
        $retty .= '<input type="number" ' . ((!is_null($value)) ? "value='" . number_format($value,2,'.','') . "'" : "" ) . ' name="' . $name . '" required="required" step=".01" min="0" id="price" class="form-control">';
        $retty .= "</div></div>";
        return $retty;
    }

    public function clockPicker( $name, $label, $time=null ) {
        if ( !is_null($time) ) { 
            $time = Time::createFromFormat('H:i',$time,'UTC');
            $val = $time->i18nFormat('H:mm', 'UTC');
            $pretval = $time->i18nFormat('h:mm a', 'UTC');
        } else {
            $val = "";
            $pretval = "";
        }

        $retty  = '<div class="form-group required">';
        $retty .= '<label class="control-label">' . $label . '</label>';
        $retty .= '<input type="text" data-role="datebox" data-datebox-mode="timebox" id="' . $name . '-dbox" class="form-control" value="' . $pretval . '" data-options=\'{"linkedField": "#' . $name . '", "overrideTimeFormat": 12, "overrideTimeOutput": "%-I:%M %p", "linkedFieldFormat": "%H:%M", "minuteStep": 15 }\'>';
        $retty .= '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $val . '" /></div>';
        return $retty;
    }

    public function datePicker( $name, $label, $time=null ) {
        if ( !is_null($time) ) { 
            $val = $time->i18nFormat('YYYY-MM-dd');
            $pretval = $time->i18nFormat('MMMM d, YYYY');
        } else {
            $val = date('Y-m-d');
            $pretval = date('F j, Y');
        }

        $retty  = '<div class="form-group required">';
        $retty .= '<label class="control-label">' . $label . '</label>';
        $retty .= '<input class="form-control" data-role="datebox" data-datebox-mode="calbox" type="text" id="'.$name.'-dbox" value="' . $pretval . '" data-options=\'{"linkedField": "#' . $name . '", "overrideDateFormat": "%B %-d, %Y", "linkedFieldFormat": "%Y-%m-%d" }\'>';
        $retty .= '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $val . '" /></div>';
        return $retty;
    }

    public function dateSPicker( $name, $label, $time=null ) {
        if ( !is_null($time) ) { 
            $val = $time->i18nFormat('YYYY-MM-dd HH:mm:ss');
            $pretval = $time->i18nFormat('MMMM d, YYYY @ HH:mm');
        } else {
            $val = date('Y-m-d 00:00:00');
            $pretval = date('F j, Y @ 00:00');
        }

        $retty  = '<div class="form-group required">';
        $retty .= '<label class="control-label">' . $label . '</label>';
        $retty .= '<input class="form-control" data-role="datebox" data-datebox-mode="slidebox" type="text" id="'.$name.'-dbox" value="' . $pretval . '" data-options=\'{"linkedField": "#' . $name . '", "minuteStep": 10, "overrideSlideFieldOrder": ["y","m","d","h","i"], "overrideDateFormat": "%B %-d, %Y @ %H:%M", "linkedFieldFormat": "%Y-%m-%d %H:%M:00" }\'>';
        $retty .= '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $val . '" /></div>';
        return $retty;
    }

    public function check($name, $check=false, $other=null, $size="normal", $dis=false) {
        $outtie  = '<div class="form-group">';
        $outtie .= '<input type="hidden" name="' . $name . '" value="0">';
        $outtie .= '<input type="checkbox" name="' . $name . '" ';
        $outtie .= 'class="bootcheck" data-size="' . $size . '" ';
        $outtie .= "value='1' ";
        $outtie .= ($check) ? "checked " : "";
        if ( is_array($other) ) {
            foreach ( $other as $key => $value ) {
                $outtie .= "data-" . $key . '="' . $value . '" ';
            }
        }
        $outtie .= ($dis ? " disabled" : "") . '></div>';
        return $outtie;
    }

    public function helpMeStart($title = "") {
        $outtie  = '<div class="modal fade" id="helpMeModal" tabindex="-1" role="dialog" aria-labelledby="helpMeLabel" aria-hidden="true">';
        $outtie .= '<div class="modal-dialog" role="document">';
        $outtie .= '<div class="modal-content">';
        $outtie .= '<div class="modal-header">';
        $outtie .= '<h5 class="modal-title" id="helpMeLabel">' . $title . "</h5>";
        $outtie .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        return $outtie . '</div><div class="modal-body">';
    }


    public function helpMeEnd() {
        return '</div><div class="modal-footer"><button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close</button></div></div></div></div>';
    }
}
?>
