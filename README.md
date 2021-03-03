
# RestDB

A (not really) rest interface for [SleekDB](https://github.com/rakibtg/SleekDB). Built for who that want to rapid prototyping without doing backend.

## How to use
1. Clone this repository on your local machine
2. install required package

All operation use `HTTP POST request`.

Basic required parameter, must be sent on every request.

    Example:
    
    {
       "app_name":"your_app_name",
       "table":"your_table_name",
       "operation":"query_builder"
    }
    
Accepted parameter for operation: `find, insert, update, delete, query_builder`.

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

Not implemented yet.

## Edit
**Warning, all updated field can't be reverted, please be cautious when selecting table name and field to update.**

### Update by id
Update by id, id must reference to document `_id`.

    {
        "operation": "update_by_id",
        "update_by_id": {
            "id": 1,
            "date": "update here"
        }
    }

### update
Update all listed `_id`, didn't need id, but all data need `_id`.

    {
        "operation": "update",
        "update": [
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

- [x] updateById

- [x] update

- [x] removeFieldsById

- [x] deleteBy

- [x] deleteById

- [ ] select

- [ ] where

- [ ] skip

- [ ] orderBy

- [ ] groupBy

- [ ] having

- [ ] search

- [ ] distinct

- [ ] join (One table join)