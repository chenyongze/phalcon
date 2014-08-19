<?php
namespace Eva\EvaComment\Controllers;

use Eva\EvaComment\Entities\Comments;
use Eva\EvaComment\Entities\Threads;

use Eva\EvaComment\Models\ThreadManager;
use Eva\EvaComment\Models\CommentManager;

use Eva\EvaEngine\Mvc\Controller\ControllerBase;

use Eva\EvaUser\Models\Login as LoginModel;

class VoteController extends ControllerBase
{
    const VIEW_FLAT = 'flat';
    const VIEW_TREE = 'tree';

    public function initialize()
    {
        $this->view->setModuleViewsDir('EvaComment', '/views');
        $this->view->setModulePartialsDir('EvaComment', '/views');
    }

    public function up()
    {

    }

    public function down()
    {

    }

}
