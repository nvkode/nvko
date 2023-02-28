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
        // Get documentation about all files in src dir.
        $projectDir = $this->getParameter('kernel.project_dir');
        $data       = (new Nvdoc($projectDir))->getFilesInformation(sprintf("%s/%s", $projectDir, 'src'));

        // Create navigation from data.
        $navigation = $this->arrayToTree(array_keys($data));

        // Get namespace name from request.
        $currentNamespace = $request->get('namespace');
        $namespaceDoc     = null;

        // Set up current namespace.
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
                'navigation'       => $navigation,
            ]
        );

    }//end index()


    /**
     * @param string[] $data
     *
     * @return array<string, mixed>
     */
    private function arrayToTree(array $data): array
    {
        $navigation = [];

        foreach ($data as $item) {
            $exploded = explode("\\", $item);
            $count    = count($exploded);
            $last     = &$navigation;

            for ($i = 0; $i < $count; $i++) {
                $part = $exploded[$i];

                if (($i + 1) < $count) {
                    $last = &$last[$part];
                    continue;
                }

                $last[$part] = $item;
            }
        }

        return $navigation;

    }


}//end class
