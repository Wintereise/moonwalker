## moonwalker-api

Custom PHP framework that ONLY includes the bare minimum functionality required to build RESTFUL PHP APIs.

### Libraries and their Documentation
1. Request Router - [league/router](http://route.thephpleague.com)
2. DI Container - [league/container](http://container.thephpleague.com)
3. ORM - [maghead/maghead](https://github.com/maghead/maghead)
4. Request validator - [vlucas/valitron](https://github.com/vlucas/valitron)
5. JWT - [lcobucci/jwt](https://github.com/lcobucci/jwt)

### Getting started

* Route definitions go in `app/Routes/routes.php`, please do not define callback routes. Any route should ultimately terminate into a controller, example is available in the route file. For advanced routing shenanigans, see the request router documentation.

* Controller definitions go in `app/Controllers`, please place your controllers in the `Moonwalker\Controllers` namespace. Your controller
   - MUST be registered in `app/Config/generic.php`'s `controllers` array. This is so we can autoinject dependencies via the IoC container.
   - MUST extend `Moonwalker\Core\Controller`

* Controller methods MUST return an instance of `Moonwalker\Core\Response` on success. Our response class is context aware, that means it will respond via lookup of content-type headers (json / xml / msgpack).
    - On failure, the framework expects an exception to be thrown.
        - Please use `Moonwalker\Core\Errors\UserFriendlyException ($message, $http_response_code)` to throw exceptions that are safe to disclose to the user.
        - Please use `Moonwalker\Core\Errors\ValidationFailedException (Array $validationFailures)` to throw exceptions that illustrate to the user why exactly their request was denied. You can get `$validationFailures` from the validator library, see `HelloWorldController::postHello` for an example. Response code is fixed to `400` for this.
        - You can throw any of `League\Route\Http\Exception\*` to define a generic HTTP error, full list is [here](https://github.com/thephpleague/route/tree/master/src/Http/Exception). These will also be disclosed to the user.
        - ANY other `Exception` thrown or normal PHP error will result in a response code of `500` and a generic response of `We are sorry, something went wrong` to the user.

* ORM \(and specifically the CLI `maghead` tool require DBAL init before they work\). Please configure your local database instance in `app/Config/database.php` 
    - Once you've updated your config, please run `vendor/bin/maghead use app/Config/database.php`
    - Make sure the defined `Schema`s show up in `vendor/bin/maghead schema list`
    - If they do, you can now build static bindings ("models") for them like `vendor/bin/maghead schema build`
    - If the build succeeds, you're now ready to build the schema in the database server with `vendor/bin/maghead sql`
    - Any time a change is made to the schema, the last two commands need to be rerun.
    
* ORM Relationships
    - The general process to do this is defined [here](https://github.com/maghead/maghead/wiki/Defining-Relationship)
    - Please note that `one` (as defined in the doc) does not exist, and you'll be forced to replace it with `hasOne` instead.
    - Please name the accessors sensibly:
        - An user may have multiple `permissions` and `roles`, thus this (below) makes sense. Read it like this: An user may have many permissions and roles.
                ```
                $this->many('permissions', 'Moonwalker\Models\PermissionAssociationSchema', 'user_id', 'id');
                $this->many('roles', 'Moonwalker\Models\RoleAssociationSchema', 'user_id', 'id');
               ```
        - An association table however has a `definition`, i.e: a pointer to what that table creates an association for. For `PermissionAssociation` and `RoleAssociation`, these thus make sense (below). A permission (or role) association ultimately belongs to a specific permission or role.
                ```        
                $this->belongsTo('definition',  'Moonwalker\Models\PermissionSchema', 'id', 'permission_id');
                $this->belongsTo('definition',  'Moonwalker\Models\RoleSchema', 'id', 'role_id');
                ```
                
* The framework's request pipeline functions like `request -> route dispatcher -> middlewares -> controllers -> response`, if you want to perform things like Unmarshalling content, or checking for JWT auth, or checking for recaptcha token existing, the suggested place to do this is via a `Middleware`. See `app/Middlewares/UnmarshalRequestBody.php` for a fully dressed example.
    - You must define the magic `__invoke` method with 3 parameters like `(ServerRequestInterface $request, ResponseInterface $response, Callable $next)`
    - At the end of your processing, you MUST either throw an `Exception` (of any kind, this halts request processing and hands off to the error handler), or return `$next ($request, $response)`. Please note that these $request and $response objects do not have to be the same ones passed into the method, you're free to make changes to them before injecting them back into the pipeline.
    - Middlewares are attached to specific routes or groups in `app/Routes/routes.php`
    
* Moonwalker\Core\Controller comes with built in Pagination support, see `core/Controller::paginate` for documentation on how to use it, and `UserController::getUsers` for a real application.