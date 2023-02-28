<?php

/**
 * Default index controller.
 *
 * @author  Mykyta Melnyk <liswelus@gmail.com>
 * @license MIT <https://github.com/nvkode/nvko/blob/development/LICENSE>
 */

declare(strict_types=1);

namespace App\Controller;

use Nvkode\Nvdoc\Nvdoc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    name: 'app_documentation_'
)]
class DocumentationController extends AbstractController
{


    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route(
        path: '/docs',
        name: 'read',
        methods: ['GET']
    )]
    public function read(Request $request): Response
    {
        $projectDir = $this->getParameter('kernel.project_dir');
        $data       = (new Nvdoc($projectDir))->getFilesInformation(sprintf("%s/%s", $projectDir, 'src'));

        $currentNamespace = $request->get('namespace');
        $namespaceDoc     = null;

        if ($currentNamespace !== null
            && trim($currentNamespace) !== ''
            && array_key_exists($currentNamespace, $data) === true
        ) {
            $namespaceDoc = $data[$currentNamespace];
        }

        return $this->render(
            '_docs/read.html.twig',
            [
                'data'             => $data,
                'namespaceDoc'     => $namespaceDoc,
                'currentNamespace' => $currentNamespace,
            ]
        );

    }//end index()


}//end class
