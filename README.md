
# RestDB

A (not really) rest interface for [SleekDB](https://github.com/rakibtg/SleekDB). Built for who that want to rapid prototyping without doing backend.

## How to use
1. Clone this repository on your local machine
2. Run on php webserver

`php -S localhost:port`

All operation use `HTTP POST request`.

Basic required parameter, must be sent on every request.

    Example:
    
    {
       "app_name":"your_app_name",
       "table":"your_table_name",
       "operation":"query_builder"
    }
    
Accepted parameter for operation: `insert, query_builder, update`.

## Insert

Add below parameter to the basic required parameter.
	
    {
       "operation":"insert",
       "data":[
	       {
		       "name":"John",
		       "age":"20"
	       },
	       {
		       "name":"Doe",
		       "age":"25"
	       }
       ]
    }
	
If you put `array of object` in `data`, it will use `insertMany()`, otherwise it will use `insert()`

## Query Builder

### select
Select column.

    {
	    "select":  ["name"],
    }

Select column as alias.
    
    {
	    "select":  {
            "alias": "original"
        }
    }

## Update
**Warning, all updated field can't be reverted, please be cautious when selecting table name and field to update.**

### Update by id
Update by id, id must reference to document `_id`.

    {
        "operation": "update",
        "id": 1,
        "update": "update_by_id",
        "data": {
            "field": "update here"
        }
    }

### update
Update all listed `_id`, didn't need id, but all data need `_id`.

    {
        "operation": "update",
        "id": 0,
        "update": "update",
        "data": [
            {
                "_id": 1
                "field": "update here"
            }
        ]
    }

## Implementation list

- [x] insert

- [x] insertMany

- [x] findAll

- [x] findById

- [x] findBy

- [x] findOneBy

- [x] select

- [x] where

- [x] skip

- [x] orderBy

- [ ] groupBy

- [ ] having

- [x] search

- [x] distinct

- [x] join (One table join)

- [x] updateById

- [x] update

- [ ] removeFieldsById

- [ ] deleteBy

- [ ] deleteById
