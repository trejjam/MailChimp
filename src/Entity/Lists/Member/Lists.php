<?php

namespace Trejjam\MailChimp\Entity\Lists\Member;

use Nette;
use Schematic;
use Trejjam;
use Trejjam\MailChimp\Entity;

/**
 * Class Lists
 *
 * @package Trejjam\MailChimp\Entity\Lists\Member
 *
 * @property-read MemberItem[] $members
 * @property-read string       $list_id
 * @property-read int          $total_items
 */
class Lists extends Schematic\Entry
{
	use Entity\LinkTrait;

	protected static $associations = [
		'_links[]'  => Entity\Link::class,
		'members[]' => MemberItem::class,
	];

	/**
	 * @return MemberItem[]|Schematic\Entries
	 */
	public function getMembers()
	{
		return $this->members;
	}
}
