# Create a module

```
TABLE=devices
php artisan make:migration create_${TABLE}_table
```
## Example
```
Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('description');
            $table->integer('devicegroup_scope');
            $table->foreignId('owner_id')->nullable()->references('id')->on('users');
        });
```
## Creation of Source Code
```
php artisan migrate

./Lara create:module database/migrations/*_create_${TABLE}_table.php
# oder, wenn das Modul nicht automatisch gebildet werden kann:
./Lara create:module database/migrations/*_create_${TABLE}_table.php --module=device
```
## Creation of a Seeder
```
php artisan make:seeder DeviceSeeder
```
- [[https://icons.getbootstrap.com|Icons]]
```
public function run(): void
    {
        SProperty::insertIfNotExists(2001, 'devicegroup', 'Computer', 10, 'C');
        SProperty::insertIfNotExists(2002, 'devicegroup', 'Router', 20, '$');
        Menuitem::insertIfNotExists('articles', 'bi bi-journals');
        Module::insertIfNotExists('Article');
    }
```

```
php artisan db:seed --class=DeviceSeeder
```
