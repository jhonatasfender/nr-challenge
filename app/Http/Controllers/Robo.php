<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Robo extends BaseController {

    private $html, $divNode, $domNode;

    private function find(\DOMDocument $dom, $class) {
        $find = new \DomXPath($dom);
        return $find->query("//*[@class='$class']");
    }

    private function file($x, $e, $f) {
        foreach ($x->attributes as $z) {
            if ($z->name == 'href') {
                $this->html .= '
                    <li style="line-height:21.3px;margin:0px 0px 3px">
                       <div>Name: ' . $e->item($f)->nodeValue . '<br></div>
                       <div><span style="font-size:12pt">File: ' . $z->value . '</span><br></div>
                    </li>';
            }
        }
    }

    private function getUrl() {
        $dom = new \DOMDocument();
        @$dom->loadHTMLFile('http://www.cnpq.br/web/guest/licitacoes?p_p_id=licitacoescnpqportlet_WAR_licitacoescnpqportlet_INSTANCE_BHfsvMBDwU0V&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=2&pagina=1&delta=1228&registros=1228');
        return $dom;
    }

    private function getDomNode($node) {
        $this->domNode = new \DOMDocument();
        $this->domNode->appendChild($this->domNode->importNode($node, true));
        $this->divNode = $this->find($this->domNode, 'licitacoes');
    }

    private function body($i) {
        $d = new \DOMDocument();
        @$d->loadHTML($this->domNode->saveHTML($this->divNode->item($i)));
        $a = $this->find($d, 'titLicitacao');           //title
        $b = $this->find($d, 'cont_licitacoes');        //objective 
        $c = $this->find($d, 'data_licitacao');         //starting
        $e = $this->find($d, 'outro-doc');              //files
        return array(
            'title' => $a,
            'objective' => $b,
            'starting' => $c,
            'files' => $e,
        );
    }

    private function allFiles($body) {
        if ($body['files']->length <> 0) {
            for ($f = 0; $f < $body['files']->length; $f++) {
                foreach ($body['files']->item($f)->childNodes as $x) {
                    if ($x->nodeName == 'a') {
                        $this->file($x, $body['files'], $f);
                    }
                }
            }
        }
    }

    public function run() {
        $this->html = '';
        $div = $this->find($this->getUrl(), 'resultado-licitacao');
        foreach ($div->item(0)->childNodes as $element) {
            $this->getDomNode($element);
            for ($i = 0; $i < $this->divNode->length; $i++) {
                $body = $this->body($i);
                $this->html .= '
                        <hr>
                        <ul style="line-height:21.3px;margin-right:0px;margin-bottom:20px;margin-left:1em;padding-right:0px;padding-left:1em">
                        <li style="line-height:21.3px;margin:0px 0px 3px">name:&nbsp;' . $body['title']->item(0)->nodeValue . '<br></li>
                        <li style="line-height:21.3px;margin:0px 0px 3px">origin: CNPQ<br></li>
                        <li style="line-height:21.3px;margin:0px 0px 3px">
                           <div>attachments:<br></div>
                           <ul style="line-height:21.3px;margin-right:0px;margin-bottom:20px;margin-left:1em;padding-right:0px;padding-left:1em;list-style-type:disc">';
                $this->allFiles($body);
                $this->html .= '
                            </ul>
                         </li>
                         <li style="line-height:21.3px;margin:0px 0px 3px">object: ' . $body['objective']->item(0)->nodeValue . '<br></li>
                         <li style="line-height:21.3px;margin:0px 0px 3px">starting_date:&nbsp;' . $body['starting']->item(0)->nodeValue . '<br></li>
                      </ul>';
            }
        }

        return $this->html;
    }

}
