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
use xpocketmc\data\runtime\RuntimeDataDescriber;
use xpocketmc\event\block\StructureGrowEvent;
use xpocketmc\item\Bamboo as ItemBamboo;
use xpocketmc\item\Fertilizer;
use xpocketmc\item\Item;
use xpocketmc\item\VanillaItems;
use xpocketmc\math\Facing;
use xpocketmc\math\Vector3;
use xpocketmc\player\Player;
use xpocketmc\world\BlockTransaction;

final class BambooSapling extends Flowable{
	use StaticSupportTrait;

	private bool $ready = false;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->bool($this->ready);
	}

	public function isReady() : bool{ return $this->ready; }

	/** @return $this */
	public function setReady(bool $ready) : self{
		$this->ready = $ready;
		return $this;
	}

	private function canBeSupportedAt(Block $block) : bool{
		$supportBlock = $block->getSide(Facing::DOWN);
		return
			$supportBlock->getTypeId() === BlockTypeIds::GRAVEL ||
			$supportBlock->hasTypeTag(BlockTypeTags::DIRT) ||
			$supportBlock->hasTypeTag(BlockTypeTags::MUD) ||
			$supportBlock->hasTypeTag(BlockTypeTags::SAND);
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($item instanceof Fertilizer || $item instanceof ItemBamboo){
			if($this->grow($player)){
				$item->pop();
				return true;
			}
		}
		return false;
	}

	private function grow(?Player $player) : bool{
		$world = $this->position->getWorld();
		if(!$world->getBlock($this->position->up())->canBeReplaced()){
			return false;
		}

		$tx = new BlockTransaction($world);
		$bamboo = VanillaBlocks::BAMBOO();
		$tx->addBlock($this->position, $bamboo)
			->addBlock($this->position->up(), (clone $bamboo)->setLeafSize(Bamboo::SMALL_LEAVES));

		$ev = new StructureGrowEvent($this, $tx, $player);
		$ev->call();
		if($ev->isCancelled()){
			return false;
		}

		return $tx->apply();
	}

	public function ticksRandomly() : bool{
		return true;
	}

	public function onRandomTick() : void{
		$world = $this->position->getWorld();
		if($this->ready){
			$this->ready = false;
			if($world->getFullLight($this->position) < 9 || !$this->grow(null)){
				$world->setBlock($this->position, $this);
			}
		}elseif($world->getBlock($this->position->up())->canBeReplaced()){
			$this->ready = true;
			$world->setBlock($this->position, $this);
		}
	}

	public function asItem() : Item{
		return VanillaItems::BAMBOO();
	}
}