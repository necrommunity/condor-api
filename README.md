# condor-api
JSON API for retrieving match and racer info for CoNDOR events.

## Current endpoints:
- https://condor.live/api/event
  - Returns information on the most recent CoNDOR event run via CoNDORbot
  ##### Paramaters
  |Name|Type|Description|
  |----------|----------|----------|
  |`/schema_name`|Optional|A valid event name|

- https://condor.live/api/events
  - Returns a list of available events

## In-progress endpoints:

- https://condor.live/api/racer
  - Returns a list of all registered racers
  ##### Paramaters
  |Name|Description|
  |----------|----------|
  |`/user_id`|A valid user Necrobot user ID|
