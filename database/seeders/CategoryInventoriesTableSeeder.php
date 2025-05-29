<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoryInventoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('category_inventories')->delete();
        
        \DB::table('category_inventories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Proteínas',
                'observation' => 'Carnes: Res, cerdo, pollo, cordero, pavo.
Pescados y Mariscos: Filetes de pescado, camarones, calamares, mejillones.
Huevos: Enteros, claras, yemas.
Proteínas Vegetales: Tofu, seitán, legumbres (lentejas, garbanzos).',
                'status' => 1,
                'created_at' => '2025-05-29 17:42:48',
                'updated_at' => '2025-05-29 17:47:29',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Frutas',
            'observation' => 'Frutas: Limón, manzana, plátano (según el tipo de cocina).',
                'status' => 1,
                'created_at' => '2025-05-29 17:43:07',
                'updated_at' => '2025-05-29 17:47:43',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Verduras',
                'observation' => 'Verduras Frescas: Cebolla, ajo, tomate, zanahoria, pimientos, lechuga, espinaca.
Hierbas y Especias Frescas: Cilantro, perejil, albahaca, romero, tomillo.',
                'status' => 1,
                'created_at' => '2025-05-29 17:43:17',
                'updated_at' => '2025-05-29 17:48:20',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Lácteos y Derivados',
            'observation' => 'Leche, crema, mantequilla, quesos (frescos, maduros, rallados), yogur.',
                'status' => 1,
                'created_at' => '2025-05-29 17:43:29',
                'updated_at' => '2025-05-29 17:48:33',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Aceites y Grasas',
            'observation' => 'Aceite de oliva, aceite vegetal, manteca de cerdo, mantequilla clarificada (ghee).',
                'status' => 1,
                'created_at' => '2025-05-29 17:43:43',
                'updated_at' => '2025-05-29 17:50:26',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Condimentos y Especias',
            'observation' => 'Sal y Pimienta (básicos).
Especias Secas: Comino, paprika, canela, orégano, curry, clavo de olor.
Salsas y Pastas: Salsa de tomate, mostaza, salsa de soja, pasta de ajo, pasta de curry.
Vinagres y Ácidos: Vinagre blanco, vinagre balsámico, jugo de limón.',
                'status' => 1,
                'created_at' => '2025-05-29 17:43:53',
                'updated_at' => '2025-05-29 17:50:39',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Caldos y Bases',
            'observation' => 'Caldo de pollo, res o vegetales (caseros o en cubos/polvo).
Concentrados (como fondo oscuro o fumet de pescado).',
                'status' => 1,
                'created_at' => '2025-05-29 17:44:18',
                'updated_at' => '2025-05-29 17:50:58',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Productos Enlatados y Conservas',
                'observation' => 'Tomates enlatados, atún, aceitunas, chiles, maíz.',
                'status' => 1,
                'created_at' => '2025-05-29 17:44:36',
                'updated_at' => '2025-05-29 17:51:17',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Granos y Harinas',
            'observation' => 'Arroz, pasta, trigo, maíz (harina de maíz, polenta).
Harina de trigo (para repostería y salsas).
Legumbres secas (frijoles, lentejas, garbanzos).',
                'status' => 1,
                'created_at' => '2025-05-29 17:45:15',
                'updated_at' => '2025-05-29 17:49:12',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Bebidas para Cocinar',
            'observation' => 'Vino (tinto/blanco para salsas), cerveza, brandy.',
                'status' => 1,
                'created_at' => '2025-05-29 17:51:37',
                'updated_at' => '2025-05-29 17:51:37',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
        ));
        
        
    }
}