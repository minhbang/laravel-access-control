# Laravel Access Control
Trang nội dung đơn giãn

## Install

* **Thêm vào file composer.json của app**
```json
	"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/minhbang/laravel-access-control"
        }
    ],
    "require": {
        "minhbang/laravel-access-control": "dev-master"
    }
```
``` bash
$ composer update
```

* **Thêm vào file config/app.php => 'providers'**
```php
	Minhbang\AccessControl\AccessControlServiceProvider::class,
```

* **Publish config và database migrations**
```bash
$ php artisan vendor:publish
$ php artisan migrate
```
## Sử dụng

> Ngoài việc được attach các `Roles` và `Permission`, user còn phải vượt qua các __luật cứng__ `Policy` để được thực hiện các actions đối với Resources

### Resources

* Resource Models use **HasPermission** và **HasPolicy** trait
* Các Resources định nghĩa **actions()** và **policies()**
* **actions():** __['create', 'read', 'update', 'delete',...]__ sẽ sync lên thành các permission của resource này
* **policies():** __['class1', 'class2',...]__ mỗi class có các method **cùng tên với action** để check thêm điều kiện có được thực hiện action đó không, nêu không có method tương ứng với action thì **mặc định là TRUE (được phép)**

### Users

* User model use HasRole trait

### Checking For Roles

You can now check if the user has required role.

```php
if ($user->is('admin')) { // you can pass an id or slug
    // or alternatively $user->hasRole('admin')
}
```

You can also do this:

```php
if ($user->isAdmin()) {
    //
}
```

And of course, there is a way to check for multiple roles:

```php
if ($user->is('admin|moderator')) { 
    /*
    | Or alternatively:
    | $user->is('admin, moderator'), $user->is(['admin', 'moderator']),
    | $user->isOne('admin|moderator'), $user->isOne('admin, moderator'), $user->isOne(['admin', 'moderator'])
    */

    // if user has at least one role
}

if ($user->is('admin|moderator', true)) {
    /*
    | Or alternatively:
    | $user->is('admin, moderator', true), $user->is(['admin', 'moderator'], true),
    | $user->isAll('admin|moderator'), $user->isAll('admin, moderator'), $user->isAll(['admin', 'moderator'])
    */

    // if user has all roles
}
```

### Checking For Permissions

```php
if ($user->can('create.users') { // you can pass an id or slug
    //
}

if ($user->canDeleteUsers()) {
    //
}
```

You can check for multiple permissions the same way as roles. You can make use of additional methods like `canOne`, `canAll` or `hasPermission`.

### Kế thừa Permissions

Các `Roles` trong cùng `role_group`, **role có level cao hơn** sẽ **kế thừa tât cả các permissions** đã attach với **role có level thấp hơn**

> Nếu không muốn kế thừa thì cho các roles có level bằng nhau

### Entity Check

Let's say you have an article and you want to edit it. This article belongs to a user (there is a column `user_id` in articles table).

```php
if ($user->allowed('article.edit', $article)) { // $user->allowedEditArticle($article)
    //
}
```

### Blade Extensions

There are four Blade extensions. Basically, it is replacement for classic if statements.

```php
@role('admin') // @if(Auth::check() && Auth::user()->is('admin'))
    // user is admin
@endrole

@permission('edit.articles') // @if(Auth::check() && Auth::user()->can('edit.articles'))
    // user can edit articles
@endpermission

@allowed('edit', $article) // @if(Auth::check() && Auth::user()->allowed('edit', $article))
    // show edit button
@endallowed

@role('admin|moderator', 'all') // @if(Auth::check() && Auth::user()->is('admin|moderator', 'all'))
    // user is admin and also moderator
@else
    // something else
@endrole
```

### Middleware

This package comes with `VerifyRole` and `VerifyPermission` middleware. You must add them inside your `app/Http/Kernel.php` file.

```php
/**
 * The application's route middleware.
 *
 * @var array
 */
protected $routeMiddleware = [
    ...
    'role' => \Minhbang\AccessControl\Middleware\VerifyRole::class,
    'permission' => \Minhbang\AccessControl\Middleware\VerifyPermission::class,
];
```

Now you can easily protect your routes.

```php
$router->get('/example', [
    'as' => 'example',
    'middleware' => 'role:admin',
    'uses' => 'ExampleController@index',
]);

$router->post('/example', [
    'as' => 'example',
    'middleware' => 'permission:edit.articles',
    'uses' => 'ExampleController@index',
]);
```

It throws `\Minhbang\AccessControl\Exceptions\RoleDeniedException`, `\Minhbang\AccessControl\Exceptions\PermissionDeniedException` or `\Minhbang\AccessControl\Exceptions\LevelDeniedException` exceptions if it goes wrong.

You can catch these exceptions inside `app/Exceptions/Handler.php` file and do whatever you want.

```php
/**
 * Render an exception into an HTTP response.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \Exception  $e
 * @return \Illuminate\Http\Response
 */
public function render($request, Exception $e)
{
    if ($e instanceof \Minhbang\AccessControl\Exceptions\RoleDeniedException) {
        // you can for example flash message, redirect...
        return redirect()->back();
    }

    return parent::render($request, $e);
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
