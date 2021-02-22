
# RestDB

A (not really) rest interface for [SleekDB](https://github.com/rakibtg/SleekDB). Built for who that want to rapid prototyping without doing backend.

## How to use

Basic required parameter:

    Example:
    
    {
       "app_name":"your_app_name",
       "table":"your_table_name",
       "operation":"query_builder"
    }
    
    Accepted parameter for operation: insert, query_builder.

## Insert

	Example:
	
    {
       "app_name":"your_app_name",
       "table":"your_table_name",
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