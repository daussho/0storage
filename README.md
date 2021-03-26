
# Petrikor

A database as a service using [SleekDB](https://github.com/rakibtg/SleekDB). Built for who that want to rapid prototyping without doing backend.

## How to use
1. Clone this repository on your local machine
2. install required package
3. run `docker-compose up -d` or use your own web server

All query operation use `HTTP POST` to `/q?query=`.

Use parameter `show_error_log=1` to use detail log on response.

Basic required parameter, must be sent on every request.

    Example:
    
    {
       "app_name":"your_app_name",
       "table":"your_table_name"
    }

## Insert

Add below parameter to the basic required parameter.
	
    {
       "query": {
            "name": "insert",
            "insert": [
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
    }
	
`insert` must be `array of object`

## Find
Add below parameter to the basic required parameter.

    {
        "query": {
        "name": "find",
            "param": {
                "criteria": null,
                "order_by": null,
                "limit": null,
                "offset": null
            }
        }
    }

### How to use

    {
        "criteria": ["name", "LIKE", "%test%"]
    }

or

    {
        "criteria": [
            ["name", "LIKE", "%test%"],
            "OR",
            ["name", "=", "john"]
        ]
    }


For complete [reference](https://sleekdb.github.io/#/fetch-data#findBy).
## Query Builder

Not implemented yet.

## Edit
**Warning, all updated field can't be reverted, please be cautious when selecting table name and field to update.**

### Update by id
Update by id, id must be reference to document `_id`.

    {
        "query": {
            "name": "edit",
            "operation": "update_by_id",
            "update_by_id": {
                "id": 2,
                "data": {
                    "url": "https://www.google.co.id/"
                }
            }
        }
    }

### update
Update all listed `_id`, all data must include `_id`.

    {
        "query": {
            "name": "edit",
            "operation": "update",
            "update": {
                "data": {
                    "url": "https://www.google.co.id/",
                    "user_id": "1",
                    "visibility": "PRIVATE",
                    "_id": 2
                }
            }
        }
    }
