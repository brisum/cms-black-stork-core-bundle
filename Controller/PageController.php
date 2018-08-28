<?php

namespace Brisum\Stork\Bundle\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class PageController extends Controller
{
    /**
     * @Route("/", defaults={"name" = "home"}, name="stork_page_home")
     * @Route("/{name}", defaults={"name" = "home"}, name="stork_page")
     */
    public function indexAction(Request $request, $name)
    {
//        /** @var EntityManager $em */
//        $em = $this->getDoctrine()->getManager();
//        /** @var Page $entity */
//        $entity = $em->getRepository('StorkPageBundle:Page')->findOneByName($name);
        $templates = $this->getParameter('stork_core.page.templates');
//
//        if (!$entity || Page::STATUS_PUBLISH != $entity->getStatus()) {
//            throw $this->createNotFoundException();
//        }
//
        $template = 'home'; // $entity->getTemplate();
        if (!array_key_exists($template, $templates)) {
            throw $this->createNotFoundException("Not Found Template \"{$template}\"");
        }

        return $this->render(
            $templates[$template],
            [
                //'entity' => $entity
            ]
        );
    }
}
