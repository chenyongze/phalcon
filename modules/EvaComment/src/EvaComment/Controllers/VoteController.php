<?php
namespace Eva\EvaComment\Controllers;

use Eva\EvaComment\Entities\Comments;
use Eva\EvaComment\Entities\Threads;

use Eva\EvaComment\Entities\Votes;
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

    public function upAction($commentId)
    {
        if($user = $this->getUserInfo()){
            $userId = $user['id'];
        }else{
            return false;
        }

        $commentManage = new CommentManager();

        $comment = $commentManage->findCommentById($commentId);

        $vote = $commentManage->createVote($comment,$userId,Votes::TYPE_UP);
        $commentManage->saveVote($vote,$comment);

        echo json_encode($vote);
        $this->view->disable();
    }

    public function downAction($commentId)
    {
        if($user = $this->getUserInfo()){
            $userId = $user['id'];
        }else{
            return false;
        }

        $commentManage = new CommentManager();
        $comment = $commentManage->findCommentById($commentId);

        $vote = $commentManage->createVote($comment,$userId,Votes::TYPE_DOWN);
        $commentManage->saveVote($vote,$comment);

        echo json_encode($vote);
        $this->view->disable();
    }

    public function userVotesAction()
    {


        $data = array('up'=>array(),'down'=>array());
        if ($user = $this->getUserInfo()) {
            $userId = $user['id'];
        }else{
            return $data;
        }

        $commentIds = $this->request->getQuery('commentIds');

        $commentManage = new CommentManager();
        $votes = $commentManage->findVotesByuserId($userId,$commentIds);

        foreach($votes as $v){
            if($v->action == Votes::TYPE_UP){
                $data['up'][] = $v->commentId;
            }
            if($v->action == Votes::TYPE_DOWN){
                $data['down'][] = $v->commentId;
            }
        }
        echo json_encode($data);
        $this->view->disable();

    }

    private function getUserInfo()
    {
        $user = new LoginModel();
        if ($user->isUserLoggedIn()) {
            $userinfo = $user->getCurrentUser();

            return $userinfo;
        }else{
            return false;
        }

    }

}
