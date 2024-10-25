<?php
/*
 * Availability class for PHP
 *
 * availabletime.class.php
 * Copyright 2024 Joaquim Homrighausen; all rights reserved.
 * Development sponsored by WebbPlatsen i Sverige AB, www.webbplatsen.se
 *
 * The AvailableTime class is free software: you may redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE v3 as
 * published by the Free Software Foundation.
 *
 * The AvailableTime class is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU AFFERO
 * GENERAL PUBLIC LICENSE v3 for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE v3
 * along with the AvailableTime class. If not, write to:
 *  The Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor
 *  Boston, MA  02110-1301, USA.
 *
 * 1.0.0 (2024-10-24) Initial revision
 * 1.0.1 (2024-10-24) Added support for ????MMDD in exception dates
 */


/**
 * See README.md and test_available_time.php for usage
 */


/* Uncomment this if you want debugging logged */
define( 'AVAILABLETIMECLASS_DEBUG', false );


class AvailableTime {

    protected $available_times = false;
    protected $exception_times = false;
    protected $excluded_days = false;

    public function __construct( $available_times, $exception_times, $excluded_days ) {
        if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
            error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . ')');
            error_log('$available_times = ' . print_r( $available_times, true ) );
            error_log('$exception_times = ' . print_r( $exception_times, true ) );
            error_log('$excluded_days = ' . print_r( $excluded_days, true ) );
        }
        $this->setAvailableTimes( $available_times );
        $this->setExceptionTimes( $exception_times );
        $this->setExcludedDays( $excluded_days );
    }

    public function setAvailableTimes( $available_times ) {
        if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
            error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): $available_times ' . print_r( $available_times, true ) );
        }
        $this->available_times = $available_times;
    }
    public function getAvailableTimes() {
        return( $this->available_times );
    }
    public function setExceptionTimes( $exception_times ) {
        if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
            error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): $exception_times ' . print_r( $exception_times, true ) );
        }
        $this->exception_times = $exception_times;
    }
    public function getExceptionTimes() {
        return( $this->exception_times );
    }
    public function setExcludedDays( $excluded_days ) {
        if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
            error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): $excluded_days ' . print_r( $excluded_days, true ) );
        }
        $this->excluded_days = $excluded_days;
    }
    public function getExcludedDays() {
        return( $this->excluded_days );
    }

    public function isAvailableTime( $check_date_time_input ) {
        try {
            $check_date_time = new DateTimeImmutable( $check_date_time_input );
        } catch( \Throwable $e ) {
            error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): Exception ' . $e->getMessage() );
            // That didn't work out, assume we're not available
            return( false );
        }
        $date_day_of_week = strtolower( $check_date_time->format( 'l' ) );
        if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
            error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): Date and time to check "' . $check_date_time_input . '", translates to "' . $check_date_time->format( 'Y-m-d H:i:s' ) . '", day of week is "' . $date_day_of_week . '"' );
        }
        if ( ! isset( $this->available_times[$date_day_of_week] ) ) {
            if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
                error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): We are not available on this weekday "' . $date_day_of_week . '"' );
            }
            // Weekday not specified, not available
            return( false );
        }
        $check_date_time_date = $check_date_time->format( 'Ymd' );
        if ( in_array( $check_date_time_date, $this->exception_times ) ) {
            if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
                error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): We are not available on "' . $check_date_time_date . '"' );
            }
            // Exact match found, not available
            return( false );
        }
        foreach( $this->exception_times as $exception_time ) {
            if ( strpos( $exception_time, '-' ) !== false ) {
                try {
                    [$range_start, $range_end] = explode( '-', $exception_time );
                    if ( empty( $range_start ) ) {
                        $range_start = '00000000';
                    } elseif( strpos( $range_start, '????' ) === 0 ) {
                        $range_start = $check_date_time->format( 'Y' ) . substr( $range_start, 4 );
                    }
                    if ( empty( $range_end ) ) {
                        $range_end = '99991231';
                    } elseif( strpos( $range_end, '????' ) === 0 ) {
                        $range_end = $check_date_time->format( 'Y' ) . substr( $range_end, 4 );
                    }
                    if ( $check_date_time_date >= $range_start && $check_date_time_date <= $range_end ) {
                        if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
                            error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): We are not available from "' . $range_start . '" to "' . $range_end . '"' );
                        }
                        // Range match found, not available
                        return( false );
                    }
                } catch( \Throwable $e ) {
                    error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): Exception ' . $e->getMessage() );
                    return( false );
                }
            } elseif ( strpos( $exception_time, '????' ) === 0 ) {
                $wildcard_check = $check_date_time->format( 'Y' ) . substr( $exception_time, 4 );
                if ( $wildcard_check === $check_date_time_date ) {
                    if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
                        error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): We are not available on "' . $check_date_time_date . '" ("' . $exception_time . '")' );
                    }
                    // Range match found, not available
                    return( false );
                }
            }
        }// foreach
        if ( in_array( $date_day_of_week, $this->excluded_days ) ) {
            if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
                error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): We are not available on this (excluded) weekday "' . $date_day_of_week . '"' );
            }
            // Weekday matches exclusion days, not available
            return( false );
        }
        $date_time_of_day = $check_date_time->format( 'Hi' );
        foreach( $this->available_times[$date_day_of_week] as $time_slot ) {
            try {
                [$start_time,$end_time] = explode( '-', $time_slot );
                if ( empty( $start_time ) ) {
                    $start_time = '0000';
                }
                if ( empty( $end_time ) ) {
                    $end_time = '2359';
                }
                if ( $date_time_of_day >= $start_time && $date_time_of_day <= $end_time ) {
                    if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
                        error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): We are available at "' . $date_time_of_day . '" on "' . $date_day_of_week . '"' );
                    }
                    return( true );
                }
            } catch( \Throwable $e ) {
                error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): Exception ' . $e->getMessage() );
                return( false );
            }
        }// foreach

        if ( defined( 'AVAILABLETIMECLASS_DEBUG' ) && AVAILABLETIMECLASS_DEBUG ) {
            error_log( basename( __FILE__ ) . '(' . __FUNCTION__ . '): We are not available at "' . $date_time_of_day . '" on "' . $date_day_of_week . '"' );
        }
        return( false );
    }// isAvailableTime

}// AvailableTime
