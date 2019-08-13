<?php

/**
 * @RoutePrefix("/hotwords")
 */

class HotwordsController extends ApiBaseController {

    /**
     * @Get("/")
     */
    public function indexAction() {
        $data = Hotwords::apiGetHotwords($this->channel_id);
        $this->_json($data);
    }
}