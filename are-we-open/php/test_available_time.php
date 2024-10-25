<?php

require_once 'availabletime.class.php';

$available_times = [
    'monday' => ['0830-1130', '1430-'],
    'tuesday' => ['0830-1045', '1230-'],
    'wednesday' => ['0830-1130', '1230-1700'],
    'thursday' => ['0830-1130', '1230-1700'],
];

$exception_dates = [
    '20241231',          // New Year's Eve
    '????1224',          // Christmas Eve
    '????1225',          // Christmas Day
    '????1001-????1031', // October, every year
];

$exception_days = [
    'tuesday',
];

$available_times = new AvailableTime( $available_times, $exception_dates, $exception_days );

$time = 'now';
if ( $available_times->isAvailableTime( $time ) ) {
    fprintf( STDOUT, "We are available on '%s'\n", $time );
} else {
    fprintf( STDOUT, "We are NOT available on '%s'\n", $time );
}

$time = '13:30';
if ( $available_times->isAvailableTime( $time ) ) {
    fprintf( STDOUT, "We are available on '%s'\n", $time );
} else {
    fprintf( STDOUT, "We are NOT available on '%s'\n", $time );
}
