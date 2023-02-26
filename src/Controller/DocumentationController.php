<?php

/**
 * Default index controller.
 *
 * @author  Mykyta Melnyk <liswelus@gmail.com>
 * @license MIT <https://github.com/nvkode/nvko/blob/development/LICENSE>
 */

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    name: 'app_documentation_'
)]
class DocumentationController extends AbstractController
{


    /**
     * @return Response
     */
    #[Route(
        path: '/docs/read',
        name: 'read',
        methods: ['GET']
    )]
    public function read(): Response
    {
        return $this->render('_docs/_read.html.twig');

    }//end index()


}//end class
