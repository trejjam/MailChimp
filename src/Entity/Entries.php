<?php
declare(strict_types=1);

namespace Trejjam\MailChimp\Entity;

use Schematic;

final class Entries extends Schematic\Entries
{
    /**
     * @return array<string, Schematic\Entry>
     */
    public function toArray() : array
    {
        $out = parent::toArray();

        /** @var AEntity|Entries|Schematic\Entry $entity */
        foreach ($out as $key => $entity) {
            if ($entity instanceof AEntity) {
                $out[$key] = $entity->toArray();
            }
            elseif ($entity instanceof self) {
                $out[$key] = $entity->toArray();
            }
        }

        return $out;
    }
}
