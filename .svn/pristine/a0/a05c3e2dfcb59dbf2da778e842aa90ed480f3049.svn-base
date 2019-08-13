<?php
    class IndexController extends  SMSBaseController
    {

        public function IndexAction()
        {
            echo Lang::_('success');
        }
        public function SendCodeAction()
        {
            if(Request::getQuery('mob') && Request::getQuery('uname') && Request::getQuery('pwd') && Request::getQuery('senceid'))
            {
                $mob = Request::getQuery('mob','string','');
                $uname = Request::getQuery('uname','string','');
                $pwd = Request::getQuery('pwd','string','');
                $senceid = Request::getQuery('senceid','int',0);
                if(!$this->valAviable($uname,$pwd))
                {
                    exit();
                }
            }
        }
        private  function valAviable($mob,$pwd)
        {
            return true;
        }
    }