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
everything starts through this method

```php
private function getUrl() {
	$dom = new \DOMDocument();
	@$dom->loadHTMLFile('http://www.cnpq.br/web/guest/licitacoes?p_p_id=licitacoescnpqportlet_WAR_licitacoescnpqportlet_INSTANCE_BHfsvMBDwU0V&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=2&pagina=1&delta=1228&registros=1228');
	return $dom;
}
```
This link is the same that was passed in the challenge, the only difference that has some parameter settings research more:
CNPQ - http://www.cnpq.br/web/guest/licitacoes 

```php
$div = $this->find($this->getUrl(), 'resultado-licitacao');
```
After loading the entire page this url, will use the method called "find," he is responsible for finding the class of this div

```php
private function find(\DOMDocument $dom, $class) {
	$find = new \DomXPath($dom);
	return $find->query("//*[@class='$class']");
}
```























