<?php
/*
 * Are We Open Plugin for Bludit (are-we-open)
 *
 * plugin.php (are-we-open)
 * Copyright 2024 Joaquim Homrighausen; all rights reserved.
 * Development sponsored by WebbPlatsen i Sverige AB, www.webbplatsen.se
 *
 * This file is part of are-we-open. are-we-open is free software.
 *
 * are-we-open is free software: you may redistribute it and/or modify it
 * under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE v3 as published by
 * the Free Software Foundation.
 *
 * are-we-open is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU AFFERO GENERAL PUBLIC LICENSE
 * v3 for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE v3
 * along with the are-we-open package. If not, write to:
 *  The Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor
 *  Boston, MA  02110-1301, USA.
 */

defined( 'BLUDIT' ) || die( 'That did not work as expected.' );

define( 'AREWEOPEN_PLUGIN_DEBUG', false );

define( 'AREWEOPEN_PLUGIN_ENABLED',                   'plugin-enabled'        );
define( 'AREWEOPEN_PLUGIN_DISABLED',                  'plugin-disabled'       );
define( 'AREWEOPEN_PLUGIN_GENERAL_STATUS_CONFIGURED', 'plugin-as-configured'  );
define( 'AREWEOPEN_PLUGIN_GENERAL_STATUS_CLOSED',     'plugin-closed'         );
define( 'AREWEOPEN_PLUGIN_DAY_MON',                   'monday'                );
define( 'AREWEOPEN_PLUGIN_DAY_TUE',                   'tuesday'               );
define( 'AREWEOPEN_PLUGIN_DAY_WED',                   'wednesday'             );
define( 'AREWEOPEN_PLUGIN_DAY_THU',                   'thursday'              );
define( 'AREWEOPEN_PLUGIN_DAY_FRI',                   'friday'                );
define( 'AREWEOPEN_PLUGIN_DAY_SAT',                   'saturday'              );
define( 'AREWEOPEN_PLUGIN_DAY_SUN',                   'sunday'                );

define( 'AREWEOPEN_PLUGIN_STATUS',                    'plugin-status'         );
define( 'AREWEOPEN_PLUGIN_GENERAL_STATUS',            'plugin-general-status' );
define( 'AREWEOPEN_PLUGIN_DAY_CONFIGURATION',         'day-configuration'     );
define( 'AREWEOPEN_PLUGIN_DAYS_ACTIVE',               'days-active'           );
define( 'AREWEOPEN_PLUGIN_CLOSED_ON_DAYS',            'closed-on-days'        );
define( 'AREWEOPEN_PLUGIN_CLOSED_ON_DATES',           'closed-on-dates'       );

class AreWeOpen extends Plugin {

    protected $areweopen_status_open = false;

    protected $interval_values = array(
        'interval-hourly',
        'interval-daily',
        'interval-weekly',
        'interval-none',
    );
    protected $plugin_status_values = array(
        AREWEOPEN_PLUGIN_ENABLED,
        AREWEOPEN_PLUGIN_DISABLED,
    );
    protected $plugin_general_status_values = array(
        AREWEOPEN_PLUGIN_GENERAL_STATUS_CONFIGURED,
        AREWEOPEN_PLUGIN_GENERAL_STATUS_CLOSED,
    );
    protected $plugin_days_values = array(
        AREWEOPEN_PLUGIN_DAY_MON,
        AREWEOPEN_PLUGIN_DAY_TUE,
        AREWEOPEN_PLUGIN_DAY_WED,
        AREWEOPEN_PLUGIN_DAY_THU,
        AREWEOPEN_PLUGIN_DAY_FRI,
        AREWEOPEN_PLUGIN_DAY_SAT,
        AREWEOPEN_PLUGIN_DAY_SUN,
    );

    public function init()  {
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
        $this->dbFields = array(
            AREWEOPEN_PLUGIN_STATUS => AREWEOPEN_PLUGIN_ENABLED,
            AREWEOPEN_PLUGIN_GENERAL_STATUS => AREWEOPEN_PLUGIN_GENERAL_STATUS_CONFIGURED,
            AREWEOPEN_PLUGIN_DAY_MON . '-schedule' => '',
            AREWEOPEN_PLUGIN_DAY_TUE . '-schedule' => '',
            AREWEOPEN_PLUGIN_DAY_WED . '-schedule' => '',
            AREWEOPEN_PLUGIN_DAY_THU . '-schedule' => '',
            AREWEOPEN_PLUGIN_DAY_FRI . '-schedule' => '',
            AREWEOPEN_PLUGIN_DAY_SAT . '-schedule' => '',
            AREWEOPEN_PLUGIN_DAY_SUN . '-schedule' => '',
            AREWEOPEN_PLUGIN_DAY_MON . '-active' => false,
            AREWEOPEN_PLUGIN_DAY_TUE . '-active' => false,
            AREWEOPEN_PLUGIN_DAY_WED . '-active' => false,
            AREWEOPEN_PLUGIN_DAY_THU . '-active' => false,
            AREWEOPEN_PLUGIN_DAY_FRI . '-active' => false,
            AREWEOPEN_PLUGIN_DAY_SAT . '-active' => false,
            AREWEOPEN_PLUGIN_DAY_SUN . '-active' => false,
            AREWEOPEN_PLUGIN_DAY_MON . '-closed' => false,
            AREWEOPEN_PLUGIN_DAY_TUE . '-closed' => false,
            AREWEOPEN_PLUGIN_DAY_WED . '-closed' => false,
            AREWEOPEN_PLUGIN_DAY_THU . '-closed' => false,
            AREWEOPEN_PLUGIN_DAY_FRI . '-closed' => false,
            AREWEOPEN_PLUGIN_DAY_SAT . '-closed' => false,
            AREWEOPEN_PLUGIN_DAY_SUN . '-closed' => false,
            AREWEOPEN_PLUGIN_CLOSED_ON_DATES => '',
        );
    }
    public function post() {
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
        if ( isset( $_POST['save'] ) ) {
            // Do some pre-processing
            foreach( $this->plugin_days_values as $k ) {
                if ( ! empty( $_POST[$k . '-schedule'] ) ) {
                    $_POST[$k . '-schedule'] = preg_replace( '/[^0-9 -]/', '', $_POST[$k . '-schedule'] );
                }
                if ( ! empty( $_POST[$k . '-active'] ) ) {
                    $_POST[$k . '-active'] = true;
                } else {
                    $_POST[$k . '-active'] = false;
                }
                if ( ! empty( $_POST[$k . '-closed'] ) ) {
                    $_POST[$k . '-closed'] = true;
                } else {
                    $_POST[$k . '-closed'] = false;
                }
            }
        }
        parent::post();
    }

    // Inspirational credit/kudos: WordPress Core
    // Function to generate the regex pattern for parsing [shortcode_tag] shortcodes, similar to WordPress' shortcode regex
    private function getCustomTagRegEx( $the_tag ) {
        return(
            '\\['                              // Opening bracket
            . '(\\[?)'                         // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($the_tag)"                    // 2: Shortcode name (e.g., "mytag")
            . '(\\b[^\\]]*?)'                  // 3: Attributes (if any), non-greedy
            . '(?:(\\/)|'                      // 4: Self-closing tag ...
            . '\\](.*?)'                       // 5: ...or closing bracket and content inside
            . '\\[\\/\\2\\])?'                 // Closing shortcode (optional for self-closing tags)
            . '(\\]?)' );                      // 6: Optional second closing bracket for escaping shortcodes: [[tag]]
    }
    // Process [shortcode_tag] shortcodes similar to WordPress' shortcode handling structure
    private function processCustomTags( $content, $tag_regex ) {
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . '): Processing content, quote is "' . $replacement . '"' );
        }
        $callback = function( $matches ) use ( $tag_regex ) {
            $tag = $matches[2];
            if ( $tag == 'areweopen_open' && ! $this->areweopen_status_open ) {
                // Open tag block, consume it if we're not open
                return( '' );
            } elseif ( $tag == 'areweopen_closed' && $this->areweopen_status_open ) {
                // Closed tag block, consume it if we're open
                return( '' );
            }
            $content = isset( $matches[5] ) ? $matches[5] : null;
            if ( $matches[3] === '/' ) {
                return( $processed_content );
            } else {
                $processed_content = $this->processCustomTags( $content, $tag_regex );
                return( $processed_content );
            }
        };
        return( preg_replace_callback( "/$tag_regex/s", $callback, $content ) );
    }
    // Make sure we skip content insdide <pre>..</pre>
    private function preProcessCustomTags( $content ) {
        $shortcode_closed = $this->getCustomTagRegEx( 'areweopen_open' );
        $shortcode_open = $this->getCustomTagRegEx( 'areweopen_closed' );
        // Split the content by <pre> tags, we will skip the content inside <pre> tags
        $parts = preg_split( '/(<pre.*?>.*?<\/pre>)/is', $content, -1, PREG_SPLIT_DELIM_CAPTURE );
        foreach( $parts as &$part ) {
            // If the part is not inside a <pre> tag, process the shortcodes
            if ( ! preg_match('/^<pre.*?>.*?<\/pre>$/is', $part ) ) {
                $part = $this->processCustomTags( $part, $shortcode_closed );
                $part = $this->processCustomTags( $part, $shortcode_open );
            }
        }
        return( implode('', $parts) );
    }
    // Create array with our entire scheduled availability
    private function generateAvailabilityData() {
        $availability = array(
            'schedule' => array(),
            'daysclosed' => array(),
            'datesclosed' => array(),
        );
        $available_times = array();
        $closed_days = array();
        foreach( $this->plugin_days_values as $k ) {
            if ( ! $this->getDayActive( $k ) ) {
                continue;
            } else {
                try {
                    $available_times[$k] = explode( ' ', $this->getDaySchedule( $k ) );
                } catch( \Throwable $e ) {
                    error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . '): Exception ' . $e->getMessage() );
                    $available_times[$k] = array();
                }
            }
            if ( $this->getDayClosed( $k ) ) {
                $closed_days[] = $k;
            }
        }// foreach
        // Create array for excluded dates
        try {
            $availability['datesclosed'] = explode( ' ', $this->getClosedOnDates() );
        } catch( \Throwable $e ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . '): Exception ' . $e->getMessage() );
            $availability['datesclosed'] = array();
        }
        $availability['schedule'] = $available_times;
        $availability['daysclosed'] = $closed_days;
        return( $availability );
    }
    /*
    public function beforeAdminLoad() {
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
    }
    */
    /*
    public function afterAdminLoad() {
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
    }
    */
    /*
    public function beforeSiteLoad() {
        global $staticContent;
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
    }
    */
    /*
    public function afterSiteLoad() {
        global $staticContent;
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
    }
    */
    /*
    public function afterAdminLoad() {
        global $staticContent;
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
    }
    */
    /*
    public function beforeAll() {
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
    }
    */
    /*
    public function siteBodyBegin() {
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
    }
    */
    /*
    public function siteBodyEnd() {
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
    }
    */
    protected function processContent() {
        $text = ob_get_clean();
        if ( $text !== false ) {
            if ( $this->getPluginGeneralStatus() === AREWEOPEN_PLUGIN_GENERAL_STATUS_CONFIGURED ) {
                try {
                    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'availabletime.class.php';
                    // Fetch availability
                    $availability = $this->generateAvailabilityData();
                    // Initialize AvailableTime object
                    $available_times = new AvailableTime( $availability['schedule'], $availability['datesclosed'], $availability['daysclosed'] );
                    // Are We Open?
                    $this->areweopen_status_open = $available_times->isAvailableTime( 'now' );
                } catch( \Throwable $e ) {
                    error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . '): Exception ' . $e->getMessage() );
                    $this->areweopen_status_open = false;
                }
            } else {
                // Temporarily closed override is active
                $this->areweopen_status_open = false;
            }
            $text = $this->preProcessCustomTags( $text );
            echo $text;
        }
    }
    public function pageBegin() {
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
        if ( $this->getPluginStatus() === AREWEOPEN_PLUGIN_ENABLED ) {
            ob_start();
        }
    }
    public function pageEnd() {
        if ( defined( 'AREWEOPEN_PLUGIN_DEBUG' ) && AREWEOPEN_PLUGIN_DEBUG ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . ')' );
        }
        if ( $this->getPluginStatus() === AREWEOPEN_PLUGIN_ENABLED ) {
            $this->processContent();
        }
    }
    // Form
    public function form() {
        global $L;
        global $site;

        $html = '';
        // Plugin status
        $current_setting = $this->getPluginStatus();
        $html .= '<div class="row">';
        $html .= '<div class="col-12 col-lg-10 col-xl-8 mb-3">';
        $html .= '<label class="form-label h6 mb-1" for="' . AREWEOPEN_PLUGIN_STATUS . '">';
        $html .= $L->get( AREWEOPEN_PLUGIN_STATUS );
        if ( $current_setting == AREWEOPEN_PLUGIN_DISABLED ) {
            $html .= ' (<span class="text-danger">' . $L->get( AREWEOPEN_PLUGIN_DISABLED ) . '</span>)';
        }
        $html .= '</label>';
        $html .= '<select class="form-select" id="' . AREWEOPEN_PLUGIN_GENERAL_STATUS . '" name="' . AREWEOPEN_PLUGIN_STATUS . '" aria-describedby="' . AREWEOPEN_PLUGIN_STATUS . 'Help">';
        if ( ! in_array( $current_setting, $this->plugin_status_values ) ) {
            $current = AREWEOPEN_PLUGIN_ENABLED;
        }
        foreach( $this->plugin_status_values as $k ) {
            $html .= '<option value="' . $k . '"';
            if ( $current_setting == $k ) {
                $html .= ' selected';
            }
            $html .= '>' . $L->get( $k ) . '</option>';
        }
        $html .= '</select>';
        $html .= '<div id="' . AREWEOPEN_PLUGIN_STATUS . 'Help" class="form-text text-muted">' . $L->get( 'help-' . AREWEOPEN_PLUGIN_STATUS ) . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        // Plugin general status
        $html .= '<div class="row">';
        $html .= '<div class="col-12 col-lg-10 col-xl-8 mb-3">';
        $html .= '<label class="form-label h6 mb-1" for="' . AREWEOPEN_PLUGIN_GENERAL_STATUS . '">' . $L->get( AREWEOPEN_PLUGIN_GENERAL_STATUS ) . '</label>';
        $html .= '<select class="form-select" id="' . AREWEOPEN_PLUGIN_GENERAL_STATUS . '" name="' . AREWEOPEN_PLUGIN_GENERAL_STATUS . '" aria-describedby="' . AREWEOPEN_PLUGIN_STATUS . 'Help">';
        $current_setting = $this->getPluginGeneralStatus();
        if ( ! in_array( $current_setting, $this->plugin_general_status_values ) ) {
            $current = AREWEOPEN_PLUGIN_GENERAL_STATUS_CONFIGURED;
        }
        foreach( $this->plugin_general_status_values as $k ) {
            $html .= '<option value="' . $k . '"';
            if ( $current_setting == $k ) {
                $html .= ' selected';
            }
            $html .= '>' . $L->get( $k ) . '</option>';
        }
        $html .= '</select>';
        $html .= '<div id="' . AREWEOPEN_PLUGIN_GENERAL_STATUS . 'Help" class="form-text text-muted">' . $L->get( 'help-' . AREWEOPEN_PLUGIN_GENERAL_STATUS ) . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        // Are We Open?
        try {
            $now_date_time = new DateTimeImmutable( 'now' );
        } catch( \Throwable $e ) {
            error_log( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) . '(' . __FUNCTION__ . '): Exception ' . $e->getMessage() );
            // That didn't work out, assume we're not available
            $now_date_time = false;
        }
        if ( $now_date_time !== false ) {
            $html .= '<div class="row">';
            $html .= '<div class="col-12 col-lg-10 col-xl-8 mt-2 mb-1">';
            require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'availabletime.class.php';
            // Fetch availability
            $availability = $this->generateAvailabilityData();
            // Initialize AvailableTime object
            $available_times = new AvailableTime( $availability['schedule'], $availability['datesclosed'], $availability['daysclosed'] );
            $now_day_of_week = strtolower( $now_date_time->format( 'l' ) );
            $html .= '<i>' . $L->get( 'weareopen-status' ) . ' ' . $L->get( 'weareopen-for-date' ) . ' ' .
                     $now_date_time->format( 'Y-m-d, H:i' ) .
                     ' (' .
                     $L->get( $now_date_time->format( 'l' ) ) . ')' . '</i><br/><strong>';
            if ( $available_times->isAvailableTime( 'now' ) ) {
                $html .= '<span class="text-success">' . $L->get( 'weareopen-open' ) . '</span>';
            } else {
                $html .= '<span class="text-danger">' . $L->get( 'weareopen-closed' ) . '</span>';
            }
            $html .= '</strong>';
            $html .= '</div>';
            $html .= '</div>';
        } else {
            $html .= '<div class="row">';
            $html .= '<div class="col-12 col-lg-10 col-xl-8 mb-1 border-left border-warning p-2" role="alert" style="margin-left: 15px !important; max-width: 75% !important; border-width: 5px !important;">';
            $html .= $L->get( 'error-unable-to-fetch-current-time' );
            $html .= '</div>';
            $html .= '</div>';
        }
        // Day configuration
        $html .= '<div class="row">';
        $html .= '<div class="col-12 col-lg-10 col-xl-8 mb-3">';
        $html .= '<label class="form-label h6 mb-0" for="' . AREWEOPEN_PLUGIN_DAY_CONFIGURATION . '">' . $L->get( 'areweopen-' . AREWEOPEN_PLUGIN_DAY_CONFIGURATION ) . '</label>';
        $html .= '<div id="' . AREWEOPEN_PLUGIN_DAY_CONFIGURATION . '" class="row">';
        $html .= '<div class="col-12">';
        $html .= '<div class="row mt-0">';
        $html .= '<div class="col-6 d-flex flex-wrap justify-content-start align-content-start mt-0">';
        $day_counter = 0;
        foreach( $this->plugin_days_values as $k ) {
            $day_counter++;
            if ( $day_counter == 6 ) {
                $html .= '</div>';
                $html .= '<div class="col-6 d-flex flex-wrap justify-content-end align-content-start">';
            }
            $html .= '<div class="flex-fill">';
            $html .= '<div class="form-check">';
            $html .= '<input class="form-check-input" type="checkbox" value="' . $k . '" aria-label="' .
                     htmlentities( $L->get( 'areweopen-day-configuration-label' ) ) . ' ' . htmlentities( $L->get( $k ) ) . '" name="' . $k . '-active" id="' . $k . '-active"';
            if ( $this->getDayActive( $k ) ) {
                $html .= ' checked';
            }
            $html .= '>';
            $html .= '<label class="form-check-label" for="' . $k . '-active">' . htmlentities( $L->get( $k ) ) . '</label>';
            $html .= '</div>';
            $html .= '<input type="text" class="form-control" value="' . htmlentities( $this->getDaySchedule( $k ) ) . '" name="' . $k . '-schedule" id="' . $k . '-schedule" aria-describedby="' . AREWEOPEN_PLUGIN_DAY_CONFIGURATION . 'Help" />';
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div id="' . AREWEOPEN_PLUGIN_DAY_CONFIGURATION . 'Help" class="mt-2 form-text text-muted">';
        $html .= $L->get( 'areweopen-help-' . AREWEOPEN_PLUGIN_DAY_CONFIGURATION ) . '<br/>';
        $html .= '<span style="font-family: monospace;" class="small">' . $L->get( 'areweopen-help-' . AREWEOPEN_PLUGIN_DAY_CONFIGURATION . '-sample' ) . '</span></div>';
        $html .= '</div>';
        $html .= '</div>';
        // Closed on specific days
        $html .= '<div class="row">';
        $html .= '<div class="col-12 col-lg-10 col-xl-8 mb-3">';
        $html .= '<label class="form-label h6 m-0" for="' . AREWEOPEN_PLUGIN_CLOSED_ON_DAYS . '">' . $L->get( 'areweopen-' . AREWEOPEN_PLUGIN_CLOSED_ON_DAYS ) . '</label>';
        $html .= '<div id="' . AREWEOPEN_PLUGIN_CLOSED_ON_DAYS . '" class="row">';
        $html .= '<div class="col-12">';
        $html .= '<div class="row">';
        $html .= '<div class="col-12 d-flex flex-wrap justify-content-start align-content-start">';
        foreach( $this->plugin_days_values as $k ) {
            $html .= '<div class="mr-2">';
            $html .= '<div class="form-check">';
            $html .= '<input class="form-check-input" type="checkbox" value="' . $k . '" aria-label="' .
                     htmlentities( $L->get( 'areweopen-closed-on-days-label' ) ) . ' ' . htmlentities( $L->get( $k ) ) . '" name="' . $k . '-closed" id="' . $k . '-closed"';
            if ( $this->getDayClosed( $k ) ) {
                $html .= ' checked';
            }
            $html .= '>';
            $html .= '<label class="form-check-label" for="' . $k . '-closed">' . htmlentities( $L->get( $k ) ) . '</label>';
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div id="' . AREWEOPEN_PLUGIN_CLOSED_ON_DAYS . 'Help" class="form-text text-muted">';
        $html .= $L->get( 'areweopen-help-' . AREWEOPEN_PLUGIN_CLOSED_ON_DAYS );
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        // Closed on specific dates
        $html .= '<div class="row">';
        $html .= '<div class="col-12 col-lg-10 col-xl-8 mb-3">';
        $html .= '<label class="form-label h6 mb-1" for="' . AREWEOPEN_PLUGIN_CLOSED_ON_DATES . '">' . $L->get( 'areweopen-' . AREWEOPEN_PLUGIN_CLOSED_ON_DATES ) . '</label>';
        $html .= '<input name="' . AREWEOPEN_PLUGIN_CLOSED_ON_DATES . '" type="text" class="form-control" aria-describedby="' . AREWEOPEN_PLUGIN_CLOSED_ON_DATES . 'Help" value="' . $this->getClosedOnDates() . '" />';
        $html .= '<div id="' . AREWEOPEN_PLUGIN_CLOSED_ON_DATES . 'Help" class="form-text text-muted">';
        $html .= $L->get( 'areweopen-help-' . AREWEOPEN_PLUGIN_CLOSED_ON_DATES ) . '<br/>';
        $html .= '<span style="font-family: monospace;" class="small">' . $L->get( 'areweopen-help-' . AREWEOPEN_PLUGIN_CLOSED_ON_DATES . '-sample' ) . '</span></div>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="row">';
        $html .= '<div class="col-12 col-lg-10 col-xl-8 p-3 mt-3 alert alert-info" role="alert" style="max-width: 65% !important; margin-left: 25px !important;">';
        $html .= '<p class="h3">' . $L->get( 'areweopen-usage-header' ) . '</p>';
        $html .= '<p>' . $L->get( 'areweopen-usage-help' ) . '</p>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public function getPluginStatus() {
        return( $this->getValue( AREWEOPEN_PLUGIN_STATUS ) );
    }
    public function getPluginGeneralStatus() {
        return( $this->getValue( AREWEOPEN_PLUGIN_GENERAL_STATUS ) );
    }
    public function getClosedOnDates() {
        return( $this->getValue( AREWEOPEN_PLUGIN_CLOSED_ON_DATES ) );
    }
    public function getDayActive( $weekday ) {
        return( $this->getValue( $weekday . '-active' ) );
    }
    public function getDayClosed( $weekday ) {
        return( $this->getValue( $weekday . '-closed' ) );
    }
    public function getDaySchedule( $weekday ) {
        return( $this->getValue( $weekday . '-schedule' ) );
    }

}
