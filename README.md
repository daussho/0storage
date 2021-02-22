
# RestDB

A (not really) rest interface for [SleekDB](https://github.com/rakibtg/SleekDB). Built for who that want to rapid prototyping without doing backend.

## How to use
All operation use `POST request`.

Basic required parameter, must be sent on every request:

    Example:
    
    {
       "app_name":"your_app_name",
       "table":"your_table_name",
       "operation":"query_builder"
    }
    
    Accepted parameter for operation: insert, query_builder.

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
All parameter listed must be sent even when empty.

### select
Select column.

    {
	    "select":  ["name"],
    }

Select column as alias.
    
    {
	    "select":  {"alias":"original"},
    }

## Implementation list

-  [x] insert

-  [x] insertMany

-  [x] select

-  [x] where

-  [x] skip

-  [x] orderBy

- [ ] groupBy

- [ ] having

-  [x] search

-  [x] distinct

-  [x] join (One table join)

- [ ] updateById

- [ ] update

- [ ] removeFieldsById

- [ ] deleteBy

- [ ] deleteById