<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        // $this->command->info('Truncating Staff, Role and Permission tables');
        $this->truncateLaratrustTables();

        $config = config('laratrust_seeder.role_structure');
        $staffPermission = config('laratrust_seeder.permission_structure');
        $mapPermission = collect(config('laratrust_seeder.permissions_map'));

        foreach ($config as $key => $modules) {

            // Create a new role
            $role = \Modules\Core\Models\Role::create([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => ucwords(str_replace('_', ' ', $key))
            ]);
            $permissions = [];

            $this->command->info('Creating Role '. strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {

                foreach (explode(',', $value) as $p => $perm) {

                    $permissionValue = $mapPermission->get($perm);

                    $permissions[] = \Modules\Core\Models\Permission::firstOrCreate([
                        'name' => $permissionValue . '-' . $module,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                    ])->id;

                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
                }
            }

            // Attach all permissions to the role
            $role->permissions()->sync($permissions);

            $this->command->info("Creating '{$key}' staff");

            // Create default staffmember for each role
            for($x=1;$x<=10;$x++)
            {
                $staff = \Modules\Core\Models\Staff::create([
                    'name' => ucwords(str_replace('_', ' ', $key).$x),
                    'email' => $key.$x.'@app.com',
                    'password' => bcrypt('password')
                ]);
                $staff->attachRole($role);
                unset($staff);
            }
        }

        // Creating staff with permissions
        if (!empty($staffPermission)) {

            foreach ($staffPermission as $key => $modules) {

                foreach ($modules as $module => $value) {

                    // Create default staff for each permission set
                    $staff = \Modules\Core\Models\Staff::create([
                        'name' => ucwords(str_replace('_', ' ', $key)),
                        'email' => $key.'@app.com',
                        'password' => bcrypt('password'),
                        'remember_token' => str_random(10),
                    ]);
                    $permissions = [];

                    foreach (explode(',', $value) as $p => $perm) {

                        $permissionValue = $mapPermission->get($perm);

                        $permissions[] = \Modules\Core\Models\Permission::firstOrCreate([
                            'name' => $permissionValue . '-' . $module,
                            'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                            'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        ])->id;

                        $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
                    }
                }

                // Attach all permissions to the staffmember
                $staff->permissions()->sync($permissions);
            }
        }
    }

    /**
     * Truncates all the laratrust tables and the staff table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('permission_role')->truncate();
        DB::table('permission_staff')->truncate();
        DB::table('role_staff')->truncate();
        \Modules\Core\Models\Staff::truncate();
        \Modules\Core\Models\Role::truncate();
        \Modules\Core\Models\Permission::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
