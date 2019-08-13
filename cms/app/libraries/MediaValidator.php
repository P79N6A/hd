<?php

class MediaValidator {

    public static function refererId($attribute, $value, $parameters) {
        $user = Auth::user();
        $value = (int)$value;
        if (!$user || $value < 0) {
            return false;
        }
        return Referer::existsUnderChannel($user->channel_id, $value);
    }

}