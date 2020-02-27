<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
      DB::transaction(function () { 
         DB::table('permissions_groups')->insert([
            'name' => 'Otros',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Proyectos',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'project-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer proyectos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F05',
            'form_title' => 'Alta de proyecto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer proyectos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'project-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear proyectos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F05',
            'form_title' => 'Alta de proyecto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear proyectos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'project-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar proyectos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F05',
            'form_title' => 'Alta de proyecto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar proyectos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'project-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar proyectos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F05',
            'form_title' => 'Alta de proyecto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar proyectos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'project-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar proyectos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F05',
            'form_title' => 'Alta de proyecto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar proyectos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'project-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear proyectos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F05',
            'form_title' => 'Alta de proyecto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear proyectos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Servicios',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'service-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer servicios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F06',
            'form_title' => 'Alta de servicio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer servicios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'service-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear servicios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F06',
            'form_title' => 'Alta de servicio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear servicios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'service-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar servicios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F06',
            'form_title' => 'Alta de servicio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar servicios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'service-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar servicios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F06',
            'form_title' => 'Alta de servicio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar servicios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'service-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar servicios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F06',
            'form_title' => 'Alta de servicio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar servicios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'service-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear servicios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F06',
            'form_title' => 'Alta de servicio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear servicios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Bancos',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'bank-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer bancos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de bancos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer bancos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'bank-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear bancos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de bancos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear bancos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'bank-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar bancos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de bancos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar bancos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'bank-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar bancos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de bancos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar bancos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'bank-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar bancos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de bancos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar bancos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'bank-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear bancos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de bancos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear bancos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Cheques recibidos',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'incoming-check-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer cheques recibidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer cheques recibidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'incoming-check-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear cheques recibidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear cheques recibidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'incoming-check-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar cheques recibidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar cheques recibidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'incoming-check-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar cheques recibidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar cheques recibidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'incoming-check-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar cheques recibidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar cheques recibidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'incoming-check-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear cheques recibidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear cheques recibidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Cheques emitidos',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'outgoing-check-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer cheques emitidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer cheques emitidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'outgoing-check-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear cheques emitidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear cheques emitidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'outgoing-check-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar cheques emitidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar cheques emitidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'outgoing-check-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar cheques emitidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar cheques emitidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'outgoing-check-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar cheques emitidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar cheques emitidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'outgoing-check-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear cheques emitidos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F011',
            'form_title' => 'Alta de cheque recibido',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear cheques emitidos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Productos',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'product-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer productos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de productos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer productos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'product-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear productos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de productos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear productos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'product-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar productos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de productos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar productos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'product-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar productos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de productos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar productos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'product-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar productos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de productos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar productos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'product-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear productos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F11',
            'form_title' => 'Alta de productos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear productos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Ventas',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Cuotas de venta',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-fee-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer cuotas de venta',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer cuotas de venta',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-fee-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear cuotas de venta',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear cuotas de venta',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-fee-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar cuotas de venta',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar cuotas de venta',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-fee-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar cuotas de venta',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar cuotas de venta',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-fee-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar cuotas de venta',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar cuotas de venta',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-fee-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear cuotas de venta',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear cuotas de venta',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Cobranzas',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'collection-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer cobranzas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de cobranza',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer cobranzas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'collection-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear cobranzas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de cobranza',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear cobranzas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'collection-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar cobranzas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de cobranza',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar cobranzas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'collection-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar cobranzas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de cobranza',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar cobranzas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'collection-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar cobranzas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de cobranza',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar cobranzas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'collection-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear cobranzas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F08',
            'form_title' => 'Alta de cobranza',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear cobranzas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Cuentas de egresos',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'expenses-account-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer cuentas de egresos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F09',
            'form_title' => 'Alta de cuenta de egreso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer cuentas de egresos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'expenses-account-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear cuentas de egresos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F09',
            'form_title' => 'Alta de cuenta de egreso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear cuentas de egresos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'expenses-account-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar cuentas de egresos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F09',
            'form_title' => 'Alta de cuenta de egreso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar cuentas de egresos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'expenses-account-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar cuentas de egresos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F09',
            'form_title' => 'Alta de cuenta de egreso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar cuentas de egresos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'expenses-account-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar cuentas de egresos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F09',
            'form_title' => 'Alta de cuenta de egreso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar cuentas de egresos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'expenses-account-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear cuentas de egresos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F09',
            'form_title' => 'Alta de cuenta de egreso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear cuentas de egresos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Compras y gastos',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer compras y gastos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F99',
            'form_title' => 'Alta de compra y gasto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer compras y gastos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear compras y gastos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F99',
            'form_title' => 'Alta de compra y gasto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear compras y gastos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar compras y gastos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F99',
            'form_title' => 'Alta de compra y gasto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar compras y gastos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar compras y gastos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F99',
            'form_title' => 'Alta de compra y gasto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar compras y gastos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar compras y gastos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F99',
            'form_title' => 'Alta de compra y gasto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar compras y gastos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear compras y gastos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F99',
            'form_title' => 'Alta de compra y gasto',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear compras y gastos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Cuotas de compra',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-fee-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer cuotas de compra',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de compra',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer cuotas de compra',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-fee-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear cuotas de compra',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de compra',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear cuotas de compra',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-fee-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar cuotas de compra',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de compra',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar cuotas de compra',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-fee-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar cuotas de compra',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de compra',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar cuotas de compra',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-fee-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar cuotas de compra',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de compra',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar cuotas de compra',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'purchase-fee-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear cuotas de compra',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F14',
            'form_title' => 'Alta de cuota de compra',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear cuotas de compra',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Facturas de ventas',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-invoice-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer facturas de ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F98',
            'form_title' => 'Alta de factura de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer facturas de ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-invoice-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear facturas de ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F98',
            'form_title' => 'Alta de factura de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear facturas de ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-invoice-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar facturas de ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F98',
            'form_title' => 'Alta de factura de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar facturas de ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-invoice-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar facturas de ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F98',
            'form_title' => 'Alta de factura de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar facturas de ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-invoice-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar facturas de ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F98',
            'form_title' => 'Alta de factura de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar facturas de ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'sale-invoice-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear facturas de ventas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F98',
            'form_title' => 'Alta de factura de venta',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear facturas de ventas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Domicilios',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'address-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer domicilios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de domicilio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer domicilios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'address-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear domicilios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de domicilio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear domicilios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'address-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar domicilios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de domicilio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar domicilios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'address-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar domicilios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de domicilio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar domicilios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'address-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar domicilios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de domicilio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar domicilios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'address-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear domicilios',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de domicilio',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear domicilios',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Personas',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'user-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer personas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F01',
            'form_title' => 'Alta de persona',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer personas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'user-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear personas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F01',
            'form_title' => 'Alta de persona',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear personas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'user-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar personas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F01',
            'form_title' => 'Alta de persona',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar personas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'user-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar personas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F01',
            'form_title' => 'Alta de persona',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar personas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'user-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar personas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F01',
            'form_title' => 'Alta de persona',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar personas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'user-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear personas',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F01',
            'form_title' => 'Alta de persona',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear personas',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Roles',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'role-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer roles',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F02',
            'form_title' => 'Alta de rol',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer roles',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'role-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear roles',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F02',
            'form_title' => 'Alta de rol',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear roles',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'role-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar roles',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F02',
            'form_title' => 'Alta de rol',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar roles',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'role-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar roles',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F02',
            'form_title' => 'Alta de rol',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar roles',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'role-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar roles',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F02',
            'form_title' => 'Alta de rol',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar roles',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'role-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear roles',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F02',
            'form_title' => 'Alta de rol',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear roles',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Permisos',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permission-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F03',
            'form_title' => 'Alta de permiso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permission-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F03',
            'form_title' => 'Alta de permiso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permission-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F03',
            'form_title' => 'Alta de permiso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permission-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F03',
            'form_title' => 'Alta de permiso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permission-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F03',
            'form_title' => 'Alta de permiso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permission-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F03',
            'form_title' => 'Alta de permiso',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_group_id=DB::table('permissions_groups')->insertGetId([
            'name' => 'Gupo de permisos',
            'description' => '',
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permissions-group-read',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Leer gupo de permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de grupo de permisos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Leer gupo de permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permissions-group-create',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Crear gupo de permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de grupo de permisos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Crear gupo de permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permissions-group-edit',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Editar gupo de permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de grupo de permisos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Editar gupo de permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permissions-group-delete',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Eliminar gupo de permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de grupo de permisos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Eliminar gupo de permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permissions-group-cancel',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Cancelar gupo de permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de grupo de permisos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Cancelar gupo de permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
         $permission_id=DB::table('permissions')->insertGetId([
           'slug' => 'permissions-group-block',
           'permissions_group_id' => $permission_group_id,
           'name' => 'Bloquear gupo de permisos',
           'description' => '',
           'created_by' => 1,
           'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $permission_id,
            'form_code' => 'F04',
            'form_title' => 'Alta de grupo de permisos',
            'name' => 'permissions',
            'period' => null,
            'due_date' => null,
            't1' => 'Bloquear gupo de permisos',
            't2' => null,
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
      });
    }
}
