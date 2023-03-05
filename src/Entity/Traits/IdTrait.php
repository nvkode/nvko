<?php

/**
 * IdTrait adds id field. Entity class should use HasLifecycleCallbacks.
 *
 * @author  Mykyta Melnyk <liswelus@gmail.com>
 * @license MIT <https://github.com/nvkode/nvdoc-bundle/blob/development/LICENSE>
 */

declare(strict_types=1);

namespace App\Entity\Traits;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{

    /**
     * Auto-generated UUID id
     *
     * @var Uuid $id UUID
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;


    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;

    }//end getId()


}
