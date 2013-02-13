<?php

namespace Brodev\VietIpsumWebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Brodev\VietIpsumWebBundle\Form\Type\GenerateType;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createForm(new GenerateType());

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/generate", name="generate_viet_ipsum")
     * @Method({"POST"})
     */
    public function generateAction()
    {
        $form = $this->createForm(new GenerateType());
        $form->bind($this->getRequest());

        $generation = $form->getData();
        $generator = $this->get('vietipsum.generator');
        $dictionary = $this->get('vietipsum.dictionary');

        $dictionary->createFromString($generation->getContent());
        $result = $generator->generate($generation->getParagraphs());

        return new Response(nl2br($result));
    }

}
