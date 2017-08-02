# Lumen API - Recipe

### Why lumen:
I am familiar with Laravel and have maintained Lumen applications in the past but I have not set up an API using Lumen from scratch, this is why I chose to use Lumen.

> **Note:**
> This is using CSV as the datasource.
> Data is stored in `storage/app/recipe-data.csv` and requires read/write permissions.
> Assumes there is a container capable of running Lumen.
> No lightbulbs were harmed in producing this API.

### Usage:
Get By Id
```
curl -i -X GET \
   -H "Content-Type:application/json" \
   -H "Authorization:Basic Z2liYm9uOmdpYmJvbg==" \
 'http://localhost:8111/api/v1/recipe/4'
```
Get By Search Criteria
```
curl -i -X GET \
   -H "Content-Type:application/json" \
   -H "Authorization:Basic Z2liYm9uOmdpYmJvbg==" \
 'http://localhost:8111/api/v1/recipe/cuisine/italian'
```
Update recipe
```
curl -i -X PUT \
   -H "Content-Type:application/json" \
   -H "Authorization:Basic Z2liYm9uOmdpYmJvbg==" \
   -d \
'{"title": "Update the slug","slug": "update-slug"}' \
 'http://localhost:8111/api/v1/recipe/1'
```
Add new recipe
```
curl -i -X POST \
   -H "Content-Type:application/json" \
   -H "Authorization:Basic Z2liYm9uOmdpYmJvbg==" \
   -d \
'{
	"box_type": "veggie",
	"title": "The title",
	"slug": "the-title",
	"short_title": "short title",
	"marketing_description": "marketing description",
	"calories_kcal": "< 333",
	"protein_grams": "110",
	"fat_grams": "220",
	"carbs_grams": "330",
	"bulletpoint1": "bulletpoint1",
	"bulletpoint2": "bulletpoint2",
	"bulletpoint3": "bulletpoint3",
	"recipe_diet_type_id": "1",
	"season": "s",
	"base": "meat",
	"protein_source": "meat",
	"preparation_time_minutes": "30",
	"shelf_life_days": "3",
	"equipment_needed": "pans",
	"origin_country": "UK",
	"recipe_cuisine": "english",
	"in_your_box": "lots of ingredients",
	"gousto_reference": "gous001"
}' \
 'http://localhost:8111/api/v1/recipe'
```
