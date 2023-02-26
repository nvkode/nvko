<?php

/**
 * Default index controller.
 *
 * @author  Mykyta Melnyk <liswelus@gmail.com>
 * @license MIT <https://github.com/nvkode/nvko/blob/development/LICENSE>
 */

declare(strict_types=1);

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    name: 'app_documentation_'
)]
class DocumentationController extends AbstractController
{

    /**
     * @var string $docsPath
     */
    private string $docsPath;


    /**
     * @param string $docsPath
     */
    public function __construct(
        string $docsPath
    ) {
        $this->docsPath = $docsPath;

    }


    /**
     * @return Response
     */
    #[Route(
        path: '/docs',
        name: 'read',
        methods: ['GET']
    )]
    public function read(): Response
    {
        $data = [];

        try {
            // Read XML file into string.
            $xmlFile = file_get_contents($this->docsPath);

            if ($xmlFile !== false) {
                // Convert xml string into an object.
                $xmlElement = simplexml_load_string($xmlFile);

                // Convert xmlElement to JSON.
                $xmlJSON = json_encode($xmlElement);

                if ($xmlJSON !== false) {
                    // Convert into associative array.
                    $data = json_decode($xmlJSON, true);
                }
            }//end if
        } catch (Exception) {
            // On error docs will be empty.
        }

        return $this->render(
            '_docs/read.html.twig',
            ['data' => $data]
        );

    }//end index()


}//end class
