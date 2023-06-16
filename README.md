# Design Patterns
This is a package to auto generate repositories, services, interface.

<br />
## Artisan Command List

<!-- List Of Command -->
<div>
  <ol>
    <li><a href="#Make-Repository">Make Repository</a></li>
    <li><a href="#Make-Service">Make Service</a></li>
    <li><a href="#Make-Trait">Make Trait</a></li>
  </ol>
</div>
<!-- End list of command -->

<br />

## Make Repository

__Create a repository Class.__\
`php artisan make:repository your-repository-name`

Example:
```
php artisan make:repository UserRepository
```
or
```
php artisan make:repository Backend/UserRepository
```

The above will create a **Repositories** directory inside the **App** directory.


__Create a repository with Interface.__\
`php artisan make:repository your-repository-name -i`

Example:
```
php artisan make:repository UserRepository -i
```
or
```
php artisan make:repository Backend/UserRepository -i
```
Here you need to put extra `-i` flag.
The above will create a **Repositories** and **Interfaces** in **Repositories** folder\
directory inside the **App** directory.

<br/>

## Make Service

__Create a Service Class.__\
`php artisan make:service your-service-name`

Example:
```
php artisan make:service UserService
```
or
```
php artisan make:service Backend/UserService
```
The above will create a **Services** directory inside the **App** directory.
<br/>



## Make Trait
__Create a Trait.__\
`php artisan make:trait your-trait-name`

Example:
```
php artisan make:trait CheckAuth
```
or
```
php artisan make:trait Backend/CheckAuth
```
<br />



## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.