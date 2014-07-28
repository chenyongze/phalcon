<?php
namespace Eva\EvaComment\Controllers;

use Eva\EvaComment\Entities\Comments;
use Eva\EvaComment\Entities\Threads;

use Eva\EvaComment\Models\ThreadManager;
use Eva\EvaComment\Models\CommentManager;

use Eva\EvaEngine\Mvc\Controller\ControllerBase;
use Eva\EvaBlog\Forms;

use Gregwar\Captcha\CaptchaBuilder;


class DemoController extends ControllerBase
{
    public function initialize()
    {
//        $this->view->setModuleLayout('WscnGold', '/views/layouts/default');
        $this->view->setModuleViewsDir('EvaComment', '/views');
        $this->view->setModulePartialsDir('EvaComment', '/views');
    }

    public function indexAction()
    {
//        $this->view->setModuleLayout('WscnGold', '/views/layouts/default');
//        $this->getDI()->get('eventsManager')->attach(
//            "view",
//            function ($event, $view) {
//                p($view);
////                exit;
//            }
//        );
    }


}
