<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'site.title',
                'display_name' => 'Título del sitio',
                'value' => 'Título del sitio',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Site',
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'site.description',
                'display_name' => 'Descripción del sitio',
                'value' => 'Descripción del sitio',
                'details' => '',
                'type' => 'text',
                'order' => 2,
                'group' => 'Site',
            ),
            2 => 
            array (
                'id' => 3,
                'key' => 'site.logo',
                'display_name' => 'Logo del sitio',
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 3,
                'group' => 'Site',
            ),
            3 => 
            array (
                'id' => 5,
                'key' => 'admin.bg_image',
                'display_name' => 'Imagen de fondo del administrador',
                'value' => 'settings/May2025/ZngacIwELlyr59mGgUb0.webp',
                'details' => '',
                'type' => 'image',
                'order' => 5,
                'group' => 'Admin',
            ),
            4 => 
            array (
                'id' => 6,
                'key' => 'admin.title',
                'display_name' => 'Título del administrador',
                'value' => 'SNACK AL PASO',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ),
            5 => 
            array (
                'id' => 7,
                'key' => 'admin.description',
                'display_name' => 'Descripción del administrador',
                'value' => 'Sistema de administracion de restaurante',
                'details' => '',
                'type' => 'text',
                'order' => 2,
                'group' => 'Admin',
            ),
            6 => 
            array (
                'id' => 8,
                'key' => 'admin.loader',
                'display_name' => 'Imagen de carga del administrador',
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 3,
                'group' => 'Admin',
            ),
            7 => 
            array (
                'id' => 9,
                'key' => 'admin.icon_image',
                'display_name' => 'Ícono del administrador',
                'value' => 'settings/May2025/FBiRtybcrTaEsbEdCXzw.png',
                'details' => '',
                'type' => 'image',
                'order' => 4,
                'group' => 'Admin',
            ),
            8 => 
            array (
                'id' => 11,
                'key' => 'system.development',
                'display_name' => 'Sistema en Mantenimiento 503',
                'value' => '0',
                'details' => NULL,
                'type' => 'checkbox',
                'order' => 1,
                'group' => 'System',
            ),
            9 => 
            array (
                'id' => 12,
                'key' => 'system.payment-date',
                'display_name' => 'Fecha de Limite de Pago',
                'value' => '2025-06-12',
                'details' => NULL,
                'type' => 'text',
                'order' => 2,
                'group' => 'System',
            ),
            10 => 
            array (
                'id' => 13,
                'key' => 'contact.name',
                'display_name' => 'Nombre',
                'value' => 'Ignacio Molina Guzman',
                'details' => NULL,
                'type' => 'text',
                'order' => 1,
                'group' => 'Contact',
            ),
            11 => 
            array (
                'id' => 14,
                'key' => 'contact.phone',
                'display_name' => 'Celular',
                'value' => '+59163285914',
                'details' => NULL,
                'type' => 'text',
                'order' => 2,
                'group' => 'Contact',
            ),
            12 => 
            array (
                'id' => 15,
                'key' => 'contact.email',
                'display_name' => 'Email',
                'value' => 'ignaciomolinaguzman20@gmail.com',
                'details' => NULL,
                'type' => 'text',
                'order' => 3,
                'group' => 'Contact',
            ),
            13 => 
            array (
                'id' => 16,
                'key' => 'contact.address',
                'display_name' => 'Dirección',
                'value' => 'Nueva Trinidad',
                'details' => NULL,
                'type' => 'text',
                'order' => 4,
                'group' => 'Contact',
            ),
            14 => 
            array (
                'id' => 17,
                'key' => 'system.payment-alert',
                'display_name' => 'Alerta de Pago',
                'value' => '1',
                'details' => NULL,
                'type' => 'checkbox',
                'order' => 3,
                'group' => 'System',
            ),
        ));
        
        
    }
}