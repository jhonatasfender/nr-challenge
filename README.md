# nr-challenge

#### Initializing the project

Access the nr-challenge folder
and type the following command

```sh
$ php artisan serve
```

Click the link below after running the above command

* [http://localhost:8000/](http://localhost:8000/)


#### Explaining about the code

To be brief and quickly put the class running on the route.

```php
Route::get('/', function () {
	$r = new \App\Http\Controllers\Robo();
    return $r->run();
});
```
