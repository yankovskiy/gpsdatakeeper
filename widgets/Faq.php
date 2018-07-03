<?php

namespace app\widgets;

use yii\base\Widget;

/**
 * Class Faq
 * Widget for display FAQ in map or backend
 * @package app\widgets
 */
class Faq extends Widget
{
    /**
     * @var bool true if widget called from backend
     */
    public $isBackend = false;

    /**
     * Content of F.A.Q.
     * @var array
     */
    private $content = [
        ['q' => 'I drew a route, how do I know its length?', 'a' => 'Just click on the line.'],
        ['q' => 'How long is the saved GPS-data stored?', 'a' => 'For registered users is unlimited. For guests is a week.'],
        ['q' => 'Why should I register?', 'a' => 'By registering you will get:<ul><li>permanent storage of your GPS-data;</li><li>the ability to edit your GPS-data.</li></ul>'],
        ['q' => 'I am developer and I want to help the project.', 'a' => 'You can found source code on the <a href="https://github.com/yankovskiy/gpsdatakeeper" target="_blank">GitHub</a>.'],
        ['q' => 'I have a question.', 'a' => 'Please, <a href="/site/contact">contact us</a>.'],
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->isBackend) {
            return $this->renderBackendFaq();
        } else {
            return $this->renderMapFaq();
        }
    }

    /**
     * Renders F.A.Q. for backend
     * @return string html code for display
     */
    private function renderBackendFaq()
    {
        $content = '<div class="box-group" id="accordion"><div class="box-group" id="accordion">';
        foreach ($this->content as $key => $question) {
            $content .= sprintf('
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse%d" aria-expanded="false" class="collapsed">
                        %s
                      </a>
                    </h4>
                  </div>
                  <div id="collapse%d" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="box-body">
                      %s
                    </div>
                  </div>
                </div>', $key, $question['q'], $key, $question['a']);
        }
        $content .= '</div></div>';

        return $content;
    }

    /**
     * Renders F.A.Q. for map
     * @return string html code for display
     */
    private function renderMapFaq()
    {
        $content = '<dl>';
        foreach ($this->content as $question) {
            $content .= '<dt>' . $question['q'] . '</dt>';
            $content .= '<dd><small>' . $question['a'] . '</small></dd>';
        }
        $content .= '</dl>';

        return $content;
    }


}