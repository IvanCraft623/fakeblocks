<div align="center">
  <h1>🧊 fakeblocks</h1>
  <p>Create and manage fakeblocks</p>
</div>

## Description:
A virion for PocketMine-MP to create and manage fake blocks. This virion indicates to the client that there is a block where there really isn't on the server side.

## Usage
Import `FakeBlockManager` class.
```php
use IvanCraft623\fakeblocks\FakeBlockManager;
```

### Register
This virion needs to listen for events, so you will need to register it, we check that it is not registered in case some other plugin has already done it
```php
if (!FakeBlockManager::isRegistered()) {
	FakeBlockManager::register($plugin);
}
```
`$plugin` is your `Plugin` object

Once registered you can do `FakeBlockManager::getInstance()` to get an instance

### Create a FakeBlock
```php
$fakeblock = FakeBlockManager::getInstance()->create(VanillaBlocks::DIAMOND(), $position);
```
`$position` is a `Position` object
`$fakeblock` will contain a `FakeBlock` object

### Add a viewer for a fake block
This function adds a viewer for the fake block
```php
$fakeblock->addViewer($player);
```

### Remove a viewer for a fake block
This function removes a viewer from the fake block
```php
$fakeblock->removeViewer($player);
```

### Get all observers of a fake block
```php
$fakeblock->getViewers();
```

### Destroy a fake block
This function will destroy the fake block and make it so the viewers can see the real block
```php
FakeBlockManager::getInstance()->destroy($fakeblock);
```
