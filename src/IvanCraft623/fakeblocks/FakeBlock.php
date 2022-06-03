<?php

declare(strict_types=1);

namespace IvanCraft623\fakeblocks;

use pocketmine\block\Block;
use pocketmine\player\Player;
use pocketmine\world\Position;
use function in_array;

class FakeBlock {

	/**
	 * @var array<int, Player>
	 */
	protected array $viewers = [];

	/**
	 * @var array<int, Player>
	 */
	protected array $blockUpdatePacketQueue = [];

	public function __construct(protected Block $block, Position $pos) {
		$block->position($pos->getWorld(), (int) $pos->x,  (int) $pos->y,  (int) $pos->z);
	}

	public function getBlock() : Block {
		return $this->block;
	}

	public function getPosition() : Position {
		return $this->block->getPosition();
	}

	public function getViewers() : array {
		return $this->viewers;
	}

	public function isViewer(Player $player) : bool {
		return isset($this->viewers[$player->getId()]);
	}

	public function addViewer(Player $player) : void {
		$this->viewers[$player->getId()] = $player;

		$world = $this->getPosition()->getWorld();
		if ($world === $player->getWorld() && in_array($player, $world->getViewersForPosition($this->getPosition()), true)) {
			$packets = FakeBlockManager::getInstance()->createBlockUpdatePackets($player, [$this]);
			foreach ($packets as $packet) {
				$player->getNetworkSession()->sendDataPacket($packet);
			}
		}
	}

	public function removeViewer(Player $player) : void {
		unset($this->viewers[$player->getId()]);

		$world = $this->getPosition()->getWorld();
		if ($world === $player->getWorld() && in_array($player, $world->getViewersForPosition($this->getPosition()), true)) {
			$packets = FakeBlockManager::getInstance()->createBlockUpdatePackets($player, [$world->getBlock($this->getPosition())]);
			foreach ($packets as $packet) {
				$player->getNetworkSession()->sendDataPacket($packet);
			}
		}
	}

	/**
	 * @internal
	 */
	public function isInBlockUpdatePacketQueue(Player $player) : bool {
		return isset($this->blockUpdatePacketQueue[$player->getId()]);
	}

	/**
	 * @internal
	 */
	public function blockUpdatePacketQueue(Player $player, bool $bool) : void {
		if ($bool) {
			$this->blockUpdatePacketQueue[$player->getId()] = $player;
		} else {
			unset($this->blockUpdatePacketQueue[$player->getId()]);
		}
	}
}