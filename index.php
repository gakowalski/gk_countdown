<?php

/*
Plugin Name: Countdown Timer
Plugin URI:  
Description: Countdown Timer
Version:     1.0.2
Author:      Grzegorz Kowalski
Author URI:  https://grzegorzkowalski.pl
*/

add_shortcode( 'countdown_timer', 'countdown_timer_shortcode' );

function countdown_timer_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'id' => 'countdown_timer',
        'tag' => 'div',
        'date' => date('Y-12-31 23:59:59'),
        'url' => '',
        'separator' => ':',
    ), $atts, 'countdown_timer' );

    $date = $atts['date'];


    ob_start(); ?>

    <div id="<?= $atts['id'] ?>_container" class="countdown_timer_container">
        <div id="<?= $atts['id'] ?>" class="countdown_timer">
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
        window.countdown_timer_interval_id = null;

        const countdown_timer_callback = () =>{
            const now = new Date().getTime();

            let diff = countdown_timer_event_date - now;

            if(diff < 0){
                if (window.countdown_timer_interval_id) {
                    clearInterval(window.countdown_timer_interval_id);
                }
                console.log('Would be redirected to: <?php echo $atts['url']; ?>');

                <?php if(is_admin_bar_showing() === false && is_customize_preview() == false): ?>
                if ("<?php echo $atts['url']; ?>" == '') {
                    window.location.reload();
                } else {
                    window.location.href = "<?php echo $atts['url']; ?>";
                }
                <?php else: ?>
                    console.log('Countdown Plugin: redirecting is disabled for logged in admins');
                    alert('Countdown Plugin: redirecting is disabled  for logged in admins');
                <?php endif; ?>
            }

            let days = Math.floor(diff / (1000*60*60*24));
            let hours = Math.floor(diff % (1000*60*60*24) / (1000*60*60));
            let minutes = Math.floor(diff % (1000*60*60)/ (1000*60));
            let seconds = Math.floor(diff % (1000*60) / 1000);

            if (days < 0) days = 0;
            if (hours < 0) hours = 0;
            if (minutes < 0) minutes = 0;
            if (seconds < 0) seconds = 0;

            //days <= 99 ? days = `0${days}` : days;
            //days <= 9 ? days = `00${days}` : days;
            days <= 9 ? days = `0${days}` : days;
            hours <= 9 ? hours = `0${hours}` : hours;
            minutes <= 9 ? minutes = `0${minutes}` : minutes;
            seconds <= 9 ? seconds = `0${seconds}` : seconds;   

            const countdown_timer_container_selector = '#<?= $atts['id'] ?>_container';

            document.querySelector(countdown_timer_container_selector + ' .days .counter').textContent = days;
            document.querySelector(countdown_timer_container_selector + ' .hours .counter').textContent = hours;
            document.querySelector(countdown_timer_container_selector + ' .minutes .counter').textContent = minutes;
            document.querySelector(countdown_timer_container_selector + ' .seconds .counter').textContent = seconds;

        }

        <?php if(is_admin_bar_showing()): ?>
            countdown_timer_callback();
        <?php else: ?>
            window.countdown_timer_interval_id = setInterval(countdown_timer_callback,1000);
        <?php endif; ?>
    </script>

    <?php
    $html = ob_get_clean();

    // if tag is not div, replace all "div" with tag
    if($atts['tag'] != 'div'){
        $html = str_replace('div', $atts['tag'], $html);
    }

    return $html;
}