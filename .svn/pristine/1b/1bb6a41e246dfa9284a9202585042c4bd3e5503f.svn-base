<?php

/**
 * 观看记录
 */
class PlayrecordController extends MyBaseController {
    public $_user_id = null;

    public function initialize() {
        disableBrowserCache();
        $user_id = My::CurrentUserUid();
        if (empty($user_id)) {
            redirect($this->_ssoUrl . '?next_action=' . urlencode(getCurrentUrl()));
        } else {
            $this->_user_id = $user_id;
        }
    }

    public function indexAction() {

    }
}

?>