<?php
function white_date($date) {
    return str_replace(':', '', str_replace('-', '', str_replace(' ', '', $date)));
}