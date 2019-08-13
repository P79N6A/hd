<?php

/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2015/10/8
 * Time: 10:37
 */
class VoteController extends ApiBaseController {
    public function indexAction() {
        if(Request::getQuery()) {
            $this->VoteAction(Request::getQuery('id'));
        }
    }

    public function VoteAction($id) {
        $all_options = [];
        $vote_options = Options::getAllOption($id)->toArray();
        foreach($vote_options as $key => $options) {
            $all_options[] = array(
                'id' => $id,
                'key' => $key + 1,
                'value' => $options['options_content'],
                'count' => $options['count']
            );
        }
        return $this->returnJson($all_options);
    }

    public function returnJson($data) {
        if(count($data)) {
            return $this->jsonResponse(200, Lang::_('success'), $data);
        } else {
            return $this->jsonResponse(404, Lang::_('not found'));
        }
    }
}
