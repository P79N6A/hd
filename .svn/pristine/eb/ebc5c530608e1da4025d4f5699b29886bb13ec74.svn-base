<?php

/**
 * @RoutePrefix("/orders")
 */
class IndexController extends ApiBaseController {

    /**
     * @Get('/')
     */
    public function indexAction() {
        echo "Orders List.", PHP_EOL;
    }

    /**
     * @Options('/')
     */
    public function infoAction() {
        echo 'Operate orders.', PHP_EOL;
    }

    /**
     * @Get("/{id:[0-9]+}")
     */
    public function getAction($id) {
        echo 'Get order ', $id, PHP_EOL;
    }

    /**
     * @Put("/{id:[0-9]+}")
     */
    public function saveAction($id) {
        echo $id, ' Saved.', PHP_EOL;
    }

    /**
     * @Delete("/{id:[0-9]+}")
     */
    public function deleteAction($id) {
        echo $id, ' Deleted.', PHP_EOL;
    }

}