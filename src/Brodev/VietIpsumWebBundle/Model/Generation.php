<?php
/**
 * Quan MT - Brodev Software
 * www.brodev.com
 */

namespace Brodev\VietIpsumWebBundle\Model;

class Generation
{

    protected $content;

    protected $paragraphs;

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setParagraphs($paragraphs)
    {
        $this->paragraphs = $paragraphs;
    }

    public function getParagraphs()
    {
        return $this->paragraphs;
    }

}
