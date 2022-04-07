Set up Project
- `composer install`
- `./vendor/bin/sail up`
- `php artisan migrate `
- `php artisan serve`

Requests waits header 'api-key' with value from .env

####Endpoints: 
- POST `/api/fixtures`

Example request:
`{
 	"teams": [
 		{
 			"name": "Grodno",
 			"attack": 29,
 			"defence": 40,
 			"accuracy": 20,
 			"goalkeeper": 20
 		},
 		{
 			"name": "Minks",
 			"attack": 40,
 			"defence": 3,
 			"accuracy": 40,
 			"goalkeeper": 20
 		},
 		{
 			"name": "Moscow",
 			"attack": 10,
 			"defence": 100,
 			"accuracy": 40,
 			"goalkeeper": 20
 		},
 		{
 			"name": "SPB",
 			"attack": 50,
 			"defence": 50,
 			"accuracy": 50,
 			"goalkeeper": 50
 		}
 	]
 }`
 
 Get teams with stats
 - GET `/api/teams`
 
 Get current week overview
 - GET `/api/week`
 
 Simulate games on current wee
 - POST `/api/week/play`
 
 Simulate all left games
 - POST `/api/week/play-all`
 
 
 Reset
 - POST `/api/reset`
 
 
 
