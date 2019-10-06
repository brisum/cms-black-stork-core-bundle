<?php

namespace BlackStork\Core\Controller;

use BlackStork\Core\Entity\Page;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class PageController extends Controller
{
    /**
     * @Route("/page/{name}", name="page")
     */
    public function indexAction(Request $request, $name)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Page $entity */
        $entity = $em->getRepository(Page::class)->findOneByName($name);
        $templates = $this->getParameter('black_stork_core.page.templates');

        if (!$entity || Page::STATUS_PUBLISH != $entity->getStatus()) {
            throw $this->createNotFoundException();
        }

        $template = $entity->getTemplate();
        if (!array_key_exists($template, $templates)) {
            throw $this->createNotFoundException("Not Found Template \"{$template}\"");
        }

        return $this->render(
            $templates[$template],
            [
                'entity' => $entity
            ]
        );
    }
}
