<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author xpocketmc Team
 * @link http://www.xpocketmc.net/
 *
 *
 */

declare(strict_types=1);

namespace xpocketmc\block;

use xpocketmc\block\utils\StaticSupportTrait;
use xpocketmc\entity\effect\EffectInstance;
use xpocketmc\entity\effect\VanillaEffects;
use xpocketmc\entity\Entity;
use xpocketmc\entity\Living;
use xpocketmc\math\Facing;

class WitherRose extends Flowable{
	use StaticSupportTrait;

	private function canBeSupportedAt(Block $block) : bool{
		$supportBlock = $block->getSide(Facing::DOWN);
		return
			$supportBlock->hasTypeTag(BlockTypeTags::DIRT) ||
			$supportBlock->hasTypeTag(BlockTypeTags::MUD) ||
			match($supportBlock->getTypeId()){
				BlockTypeIds::NETHERRACK,
				BlockTypeIds::SOUL_SAND,
				BlockTypeIds::SOUL_SOIL => true,
				default => false
			};
	}

	public function hasEntityCollision() : bool{ return true; }

	public function onEntityInside(Entity $entity) : bool{
		if($entity instanceof Living && !$entity->getEffects()->has(VanillaEffects::WITHER())){
			$entity->getEffects()->add(new EffectInstance(VanillaEffects::WITHER(), 40));
		}
		return true;
	}

	public function getFlameEncouragement() : int{ return 60; }

	public function getFlammability() : int{ return 100; }
}