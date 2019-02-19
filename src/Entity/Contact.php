<?php

namespace Trejjam\MailChimp\Entity;

use Nette;
use Trejjam;
use Schematic;

/**
 * @property-read string $company
 * @property-read string $addr1
 * @property-read string $addr2
 * @property-read string $city
 * @property-read string $state
 * @property-read string $zip
 * @property-read string $country
 */
final class Contact extends Schematic\Entry
{

}
