<?php

namespace Wscn\Controllers;

use Eva\EvaBlog\Models\Post;
use Eva\EvaLivenews\Models;
use Eva\EvaEngine\Exception;
use Phalcon\Http\Client\Request;
use Phalcon\Mvc\View;

class WidgetController extends ControllerBase
{
    public function quotesAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $provider  = Request::getProvider();
        $provider->setBaseUri('http://api.markets.wallstreetcn.com/v1/');
        try {
            $response = $provider->get('quotes.json');
        } catch (\Exception $e) {
            return $this->view->setVar('quotes', array());
        }
        if($response->header->statusCode != 200) {
            return $this->view->setVar('quotes', array());
        }
        $quotes = json_decode($response->body);
        $quotes = $quotes->results;
        $selectedQuotes = $this->getDI()->getConfig()->wscn->sidebar->quotes;
        foreach($quotes as $key => $quote) {
            $symbol = $quote->symbol;
            if(isset($selectedQuotes->$symbol)) {
                $quote->keyword = $selectedQuotes->$symbol->keyword;
                $selectedQuotes->$symbol = $quote;
            }
        }
        $this->view->setVar('quotes', $selectedQuotes);
        //$this->view->changeRender('partial/sidebar');
        return $selectedQuotes;
    }

    public function sideareaAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $query = array(
            'q' => $this->request->getQuery('q', 'string'),
        );
        $this->view->setVar('query', $query);

        $news = new Models\NewsManager();
        $newsSet = $news->findNews($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $newsSet,
            "limit"=> 20,
            "page" => 1
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        $this->view->setVar('pager', $pager);
    }
}
