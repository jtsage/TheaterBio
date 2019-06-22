<?php
    function escapeString($string) {
        return preg_replace('/([\,;])/','\\\$1', $string);
    }

    echo "BEGIN:VCALENDAR\r\n";
    echo "VERSION:2.0\r\n";
    echo "PRODID:-//JTSage.com//TDTracX//EN\r\n";
    echo "CALSCALE:GREGORIAN\r\n";
    echo "METHOD:PUBLISH\r\n";

    foreach ( $events as $event ) {

        echo "BEGIN:VEVENT\r\n";
        echo "UID:" . $event->created_at->i18NFormat("yyyyMMdd'T'HHmmss'Z'", 'UTC') . "-" . $event->id . "@" . $_SERVER['HTTP_HOST'] . "\r\n";

        if ( $event->all_day ) {
            $date1 = strtotime($event->date);
            $date2 = $date1 + (60*60*24);
            echo "DTSTART;VALUE=DATE:" . date('Ymd', $date1) . "\r\n";
            echo "DTEND;VALUE=DATE:" . date('Ymd', $date2) . "\r\n";
        } else {
            $date0 = strtotime($event->date . " " . $event->start_time->i18nFormat("H:mm", 'UTC'));
            //$date1 = strtotime($event->date . " " . $event->start_time->modify($real_offset . " seconds")->i18nFormat("H:mm", 'UTC'));
            $date2 = strtotime($event->date . " " . $event->end_time->i18nFormat("H:mm", 'UTC'));
            //$date3 = strtotime($event->date . " " . $event->end_time->modify($real_offset . " seconds")->i18nFormat("H:mm", 'UTC'));
            echo "DTSTART;TZID=America/New_York:".date('Ymd\THis', $date0)."\r\n";
            //echo "DTSTART:".date('Ymd\THis\Z', $date1)."\r\n";
            echo "DTEND;TZID=America/New_York:".date('Ymd\THis', $date2)."\r\n";
            //echo "DTEND:".date('Ymd\THis\Z', $date3)."\r\n";
        }
        echo "DTSTAMP:" . $event->created_at->i18NFormat("yyyyMMdd'T'HHmmss'Z'", 'UTC') . "\r\n";
        echo "CLASS:PUBLIC\r\n";
        echo "ORGANIZER;CN=TDTracX:MAILTO:noreply@tdtrac.com\r\n";
        echo "DESCRIPTION:" . escapeString($event->note) . "\r\n";
        echo "SEQUENCE:0\r\n";
        echo "STATUS:CONFIRMED\r\n";
        echo "SUMMARY:" . escapeString($event->title) . "\r\n";
        echo "END:VEVENT\r\n";
    }

    echo "END:VCALENDAR\r\n"; ?>