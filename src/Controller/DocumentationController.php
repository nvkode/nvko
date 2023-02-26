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
use Symfony\Component\HttpFoundation\Request;
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
        $data = $this->parseDocumentation();

        return $this->render(
            '_docs/read.html.twig',
            [
                'data' => $data,
                'file' => $this->convertDocumentation($data, $request->get('hash')),
            ]
        );

    }//end index()


    /**
     * @param array<string, mixed> $property
     *
     * @return array<string, mixed>
     */
    private function getPropertyInformation(array $property): array
    {
        $docblock = $property['docblock'];
        $tags     = $docblock['tag'];
        $type     = null;

        if (empty($tags) === false) {
            if (count($tags) > 1) {
                foreach ($tags as $tag) {
                    if (array_key_exists('type', $tag) === true) {
                        $type = $tag['type'];
                        break;
                    }
                }
            } else if (array_key_exists('@attributes', $tags) === true
                && array_key_exists('type', $tags['@attributes']) === true
            ) {
                $type = $docblock['tag']['@attributes']['type'];
            }
        }

        return [
            'visibility'       => $property['@attributes']['visibility'],
            'name'             => $property['name'],
            'description'      => implode('.', $docblock['description']),
            'long-description' => implode('.', $docblock['long-description']),
            'type'             => $type,
        ];

    }//end getPropertyInformation()


    /**
     * @return array<string, mixed>
     */
    private function parseDocumentation(): array
    {
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

                    if (is_array($data) === true) {
                        return $data;
                    }
                }
            }//end if
        } catch (Exception) {
            // On error docs will be empty.
        }

        return [];

    }//end parseDocumentation()


    /**
     * @param array<string, mixed> $data
     * @param string|null          $fileHash
     *
     * @return array<string, mixed>|null
     */
    private function convertDocumentation(array $data, ?string $fileHash): array|null
    {
        $file = null;

        if (empty($data) === false && $fileHash !== null) {
            foreach ($data['file'] as $file) {
                if ($file['@attributes']['hash'] === $fileHash) {
                    $parsedProperties = [];
                    $properties       = [];

                    if (array_key_exists('property', $file['class']) === true) {
                        $properties = $file['class']['property'];
                    }

                    if (array_key_exists('name', $properties) === true) {
                        $parsedProperties[] = $this->getPropertyInformation($properties);
                    } else if (empty($properties) !== true) {
                        foreach ($properties as $property) {
                            $parsedProperties[] = $this->getPropertyInformation($property);
                        }
                    }

                    $file = [
                        'path'       => $file['@attributes']['path'],
                        'name'       => $file['class']['name'],
                        'extends'    => $file['class']['extends'],
                        'properties' => $parsedProperties,
                    ];
                    break;
                }
            }
        }

        return $file;

    }


}//end class
