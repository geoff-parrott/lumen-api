# Lumen API - Recipe

### Why lumen:
I am familiar with Laravel and have maintained Lumen applications in the past but I have not set up an API using Lumen from scratch, this is why I chose to use Lumen.

> **Note:**
> This is using CSV as the datasource for the purpose of this exercise as the brief requested.
> Data is stored in `storage/app/recipe-data.csv` and requires read/write permissions.
> Assumes there is a container capable of running Lumen.
> No lightbulbs were harmed in producing this API.

### Usage:
Get By Id
```
curl -i -X GET \
   -H "Content-Type:application/json" \
   -H "Authorization:Basic Z2liYm9uOmdpYmJvbg==" \
 'http://YOUR_SERVER_DOMAIN/api/v1/recipe/4'
```
Get By Search Criteria
```
curl -i -X GET \
   -H "Content-Type:application/json" \
   -H "Authorization:Basic Z2liYm9uOmdpYmJvbg==" \
 'http://YOUR_SERVER_DOMAIN/api/v1/recipe/cuisine/italian'
```
Update recipe
```
curl -i -X PUT \
   -H "Content-Type:application/json" \
   -H "Authorization:Basic Z2liYm9uOmdpYmJvbg==" \
   -d \
'{"title": "Update the slug","slug": "update-slug"}' \
 'http://YOUR_SERVER_DOMAIN/api/v1/recipe/1'
```
Add new recipe
```
curl -i -X POST \
   -H "Content-Type:application/json" \
   -H "Authorization:Basic Z2liYm9uOmdpYmJvbg==" \
   -d \
'{
	"box_type": "veggie",
	"title": "Eggplant Parmegana",
	"slug": "the-title",
	"short_title": "Aubergene",
	"marketing_description": "Yummy",
	"calories_kcal": "111",
	"protein_grams": "222",
	"fat_grams": "333",
	"carbs_grams": "444",
	"bulletpoint1": "bulletpoint 1",
	"bulletpoint2": "bulletpoint 2",
	"bulletpoint3": "bulletpoint 3",
	"recipe_diet_type_id": "1",
	"season": "spring",
	"base": "veggie",
	"protein_source": "cheese",
	"preparation_time_minutes": "30",
	"shelf_life_days": "3",
	"equipment_needed": "pans, oven",
	"origin_country": "UK",
	"recipe_cuisine": "english",
	"in_your_box": "lots of ingredients",
	"gousto_reference": "gous001"
}' \
 'http://YOUR_SERVER_DOMAIN/api/v1/recipe'
```
