# WorldCommandBlocker
PocketMine-MP plugin that blocks specific commands in configured worlds.

**Configuration**

You can define the blocked commands for each world directly in the configuration file.

**Example config.yml**

worlds:

  lobby:
  
    - "fly"
    - "gamemode"
    - "tp"
  factions:
    - "fly"
