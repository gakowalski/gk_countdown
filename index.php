<?php

/*
Plugin Name: Countdown Timer
Plugin URI:  
Description: Countdown Timer
Version:     1.0.0
Author:      Grzegorz Kowalski
Author URI:  https://grzegorzkowalski.pl
*/

add_shortcode( 'countdown_timer', 'countdown_timer_shortcode' );

function countdown_timer_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'date' => date('Y-12-31 23:59:59'),
        'url' => '',
        'separator' => ':',
    ), $atts, 'countdown_timer' );

    $date = $atts['date'];

    ob_start(); ?>

    <div id="countdown_timer_container">
        <div id="countdown_timer">
            <div class="box days">
                <div class="counter">00</div>
                <div class="label">dni</div>
            </div>
            <div class="separator">
                <?php echo $atts['separator']; ?>
            </div>
            <div class="box hours">
                <div class="counter">00</div>
                <div class="label">godzin</div>
            </div>
            <div class="separator">
                <?php echo $atts['separator']; ?>
            </div>
            <div class="box minutes">
                <div class="counter">00</div>
                <div class="label">minut</div>
            </div>
            <div class="separator">
                <?php echo $atts['separator']; ?>
            </div>
            <div class="box seconds">
                <div class="counter">00</div>
                <div class="label">sekund</div>
            </div>
        </div>
    </div>

    <script>
        const countdown_timer_event_date = new Date("<?php echo $date; ?>").getTime();

        const countdown_timer_callback = () =>{
            const now = new Date().getTime();
            let diff = countdown_timer_event_date - now;
            if(diff < 0){
                // redirect browser to url
                window.location.href = "<?php echo $atts['url']; ?>";
            }

            let days = Math.floor(diff / (1000*60*60*24));
            let hours = Math.floor(diff % (1000*60*60*24) / (1000*60*60));
            let minutes = Math.floor(diff % (1000*60*60)/ (1000*60));
            let seconds = Math.floor(diff % (1000*60) / 1000);

            //days <= 99 ? days = `0${days}` : days;
            //days <= 9 ? days = `00${days}` : days;
            days <= 9 ? days = `0${days}` : days;
            hours <= 9 ? hours = `0${hours}` : hours;
            minutes <= 9 ? minutes = `0${minutes}` : minutes;
            seconds <= 9 ? seconds = `0${seconds}` : seconds;   

            document.querySelector('#countdown_timer_container .days .counter').textContent = days;
            document.querySelector('#countdown_timer_container .hours .counter').textContent = hours;
            document.querySelector('#countdown_timer_container .minutes .counter').textContent = minutes;
            document.querySelector('#countdown_timer_container .seconds .counter').textContent = seconds;

        }
        countdown_timer_callback();
        setInterval(countdown_timer_callback,1000);
    </script>

    <?php
    $html = ob_get_clean();

    return $html;
}