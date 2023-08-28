<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblMediaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_media')->delete();
        
        \DB::table('tbl_media')->insert(array (
            0 => 
            array (
                'id' => 9,
                'model_type' => 'App\\Model\\Setting',
                'model_id' => 3,
                'collection_name' => 'setting',
                'name' => 'koala-7656380_1280',
                'file_name' => 'koala-7656380_1280.webp',
                'mime_type' => 'image/webp',
                'disk' => 'digitalocean',
                'size' => 1997838,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 7,
                'created_at' => '2023-08-18 16:38:18',
                'updated_at' => '2023-08-18 16:38:18',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => '68bacb0d-6f7b-4ebc-ade9-30fca04d701e',
                'generated_conversions' => '[]',
            ),
            1 => 
            array (
                'id' => 10,
                'model_type' => 'App\\Model\\Setting',
                'model_id' => 4,
                'collection_name' => 'setting',
                'name' => 'koala-7656380_1280',
                'file_name' => 'koala-7656380_1280.webp',
                'mime_type' => 'image/webp',
                'disk' => 'digitalocean',
                'size' => 1997838,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 3,
                'created_at' => '2023-08-18 16:43:46',
                'updated_at' => '2023-08-18 16:43:46',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => 'bb22127d-2aca-4a09-a317-a89e0c0b7860',
                'generated_conversions' => '[]',
            ),
            2 => 
            array (
                'id' => 11,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1,
                'collection_name' => 'collect_media',
                'name' => '49470d436f32d7d36ab2e706d4f0a963',
                'file_name' => '49470d436f32d7d36ab2e706d4f0a963.jpeg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 52234,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2023-08-23 16:13:44',
                'updated_at' => '2023-08-23 16:13:44',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => '42fac384-1b51-43da-8c46-ad18a0d63586',
                'generated_conversions' => '[]',
            ),
            3 => 
            array (
                'id' => 12,
                'model_type' => 'App\\Model\\SyncCollect',
                'model_id' => 1,
                'collection_name' => 'sync_collect_media',
                'name' => '49470d436f32d7d36ab2e706d4f0a963',
                'file_name' => '49470d436f32d7d36ab2e706d4f0a963.jpeg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 52234,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 2,
                'created_at' => '2023-08-23 16:15:54',
                'updated_at' => '2023-08-23 16:15:18',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => 'b60f1291-7aaf-4f95-8227-be2e156eefd1',
                'generated_conversions' => '[]',
            ),
            4 => 
            array (
                'id' => 13,
                'model_type' => 'App\\Model\\Company',
                'model_id' => 1,
                'collection_name' => 'company_logo',
                'name' => '1635075356055',
                'file_name' => '1635075356055.jpeg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 4533,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2023-08-23 16:17:51',
                'updated_at' => '2023-08-23 16:17:51',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => 'b8104d70-70e4-4452-a823-5ce2b167f2c1',
                'generated_conversions' => '{"full": true, "thumb": true}',
            ),
            5 => 
            array (
                'id' => 14,
                'model_type' => 'App\\Model\\Worker',
                'model_id' => 1,
                'collection_name' => 'worker_media',
                'name' => 'Ali',
                'file_name' => 'Ali.webp',
                'mime_type' => 'image/webp',
                'disk' => 'digitalocean',
                'size' => 7616,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2023-08-23 17:30:21',
                'updated_at' => '2023-08-23 17:30:21',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => '4dfee1ff-a797-4f47-a641-2c21dece0f9e',
                'generated_conversions' => '{"full": true}',
            ),
            6 => 
            array (
                'id' => 15,
                'model_type' => 'App\\Model\\Worker',
                'model_id' => 3,
                'collection_name' => 'worker_media',
                'name' => 'mutu',
                'file_name' => 'mutu.jpeg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 4758,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2023-08-23 17:30:38',
                'updated_at' => '2023-08-23 17:30:38',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => '58579ff1-ad02-4373-8b22-6d11dd1fa644',
                'generated_conversions' => '{"full": true}',
            ),
            7 => 
            array (
                'id' => 16,
                'model_type' => 'App\\Model\\Worker',
                'model_id' => 2,
                'collection_name' => 'worker_media',
                'name' => 'Ah meng',
                'file_name' => 'Ah-meng.png',
                'mime_type' => 'image/png',
                'disk' => 'digitalocean',
                'size' => 4747,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2023-08-23 17:31:01',
                'updated_at' => '2023-08-23 17:31:01',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => '8536e7b5-da1f-4958-8273-179507304c93',
                'generated_conversions' => '{"full": true}',
            ),
            8 => 
            array (
                'id' => 30605,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1383,
                'collection_name' => 'collect_media',
                'name' => 'jpgkwpOHE',
                'file_name' => 'rn_image_picker_lib_temp_881a6f51-82e2-4633-86ae-90ee4086fc56.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 26251,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 28516,
                'created_at' => '2022-04-11 14:23:41',
                'updated_at' => '2022-04-11 14:23:41',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => '7471e474-c03c-4877-8161-c3b2743ec984',
                'generated_conversions' => '[]',
            ),
            9 => 
            array (
                'id' => 30606,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1384,
                'collection_name' => 'collect_media',
                'name' => 'jpgtAbG2F',
                'file_name' => 'rn_image_picker_lib_temp_f8099640-54cd-4dda-a33c-9572b3b44b7a.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 26059,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 28517,
                'created_at' => '2022-04-11 14:23:41',
                'updated_at' => '2022-04-11 14:23:41',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => '9ed9be22-c218-41b0-801f-3d5ef65bdda1',
                'generated_conversions' => '[]',
            ),
            10 => 
            array (
                'id' => 30607,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1385,
                'collection_name' => 'collect_media',
                'name' => 'jpgjB0iMF',
                'file_name' => 'rn_image_picker_lib_temp_8ff68a79-d465-4e4a-986e-e52ecbcdd6d5.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 18304,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 28518,
                'created_at' => '2022-04-11 14:23:41',
                'updated_at' => '2022-04-11 14:23:41',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => 'cffde399-19d7-45ff-9231-d541478d19bb',
                'generated_conversions' => '[]',
            ),
            11 => 
            array (
                'id' => 30608,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1386,
                'collection_name' => 'collect_media',
                'name' => 'jpgCCoNuG',
                'file_name' => 'rn_image_picker_lib_temp_8c82041f-05a1-4f8d-ae11-7c9561cd12a8.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 14424,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 28519,
                'created_at' => '2022-04-11 14:23:41',
                'updated_at' => '2022-04-11 14:23:41',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => 'ae580175-fa46-4f86-ae32-92a49e388ea5',
                'generated_conversions' => '[]',
            ),
            12 => 
            array (
                'id' => 30609,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1387,
                'collection_name' => 'collect_media',
                'name' => 'jpgRkTflH',
                'file_name' => 'rn_image_picker_lib_temp_e5676f5f-5443-45a7-8394-c2e49a94311b.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 15059,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 28520,
                'created_at' => '2022-04-11 14:23:42',
                'updated_at' => '2022-04-11 14:23:42',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => '3c49622e-dd99-4114-ae6a-9fce0948cda3',
                'generated_conversions' => '[]',
            ),
            13 => 
            array (
                'id' => 30610,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1388,
                'collection_name' => 'collect_media',
                'name' => 'jpgvNTzRH',
                'file_name' => 'rn_image_picker_lib_temp_585d993a-61b7-49db-93cd-579077e57d2b.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 25101,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 28521,
                'created_at' => '2022-04-11 14:23:42',
                'updated_at' => '2022-04-11 14:23:42',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => '8a8ad448-1693-4608-868e-c9f44c348ee7',
                'generated_conversions' => '[]',
            ),
            14 => 
            array (
                'id' => 30611,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1389,
                'collection_name' => 'collect_media',
                'name' => 'jpgkh7VNF',
                'file_name' => 'rn_image_picker_lib_temp_979c2347-9208-403d-a39e-61a7235bc973.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 23695,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 28522,
                'created_at' => '2022-04-11 14:23:42',
                'updated_at' => '2022-04-11 14:23:42',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => '2e62754c-2932-40a0-935f-69712bdf30c8',
                'generated_conversions' => '[]',
            ),
            15 => 
            array (
                'id' => 30612,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1390,
                'collection_name' => 'collect_media',
                'name' => 'jpgRbL3qG',
                'file_name' => 'rn_image_picker_lib_temp_ea82f663-4cb6-463d-9ee0-7175eb15aaa9.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 22401,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 28523,
                'created_at' => '2022-04-11 14:23:42',
                'updated_at' => '2022-04-11 14:23:42',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => '3047edbf-f276-496c-81a7-bfa46e4cbff0',
                'generated_conversions' => '[]',
            ),
            16 => 
            array (
                'id' => 30613,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1391,
                'collection_name' => 'collect_media',
                'name' => 'jpg8Sfu3H',
                'file_name' => 'rn_image_picker_lib_temp_64791882-7a54-493a-aa7a-b78b6b578db1.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 25050,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 28524,
                'created_at' => '2022-04-11 14:23:42',
                'updated_at' => '2022-04-11 14:23:42',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => 'aa0e7941-ef73-46bd-bae8-546e6da2c75d',
                'generated_conversions' => '[]',
            ),
            17 => 
            array (
                'id' => 30614,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1392,
                'collection_name' => 'collect_media',
                'name' => 'jpgVW1JLE',
                'file_name' => 'rn_image_picker_lib_temp_a1ead557-4d02-4f6e-818e-929a8ef94f71.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 27727,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 28525,
                'created_at' => '2022-04-11 14:23:42',
                'updated_at' => '2022-04-11 14:23:42',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => 'b3ffc3b2-63bc-4612-9af6-cd5c675d611a',
                'generated_conversions' => '[]',
            ),
            18 => 
            array (
                'id' => 30615,
                'model_type' => 'App\\Model\\Collect',
                'model_id' => 1393,
                'collection_name' => 'collect_media',
                'name' => 'jpgqJ92SE',
                'file_name' => 'rn_image_picker_lib_temp_1927d311-3e0e-42dd-8054-9c1229cdccbd.jpg',
                'mime_type' => 'image/jpeg',
                'disk' => 'digitalocean',
                'size' => 33406,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'responsive_images' => '[]',
                'order_column' => 28526,
                'created_at' => '2022-04-11 14:23:42',
                'updated_at' => '2022-04-11 14:23:42',
                'setting_language_id' => NULL,
                'conversions_disk' => 'digitalocean',
                'uuid' => 'e684fabe-5a4a-462d-bb72-fb9e87db3b1e',
                'generated_conversions' => '[]',
            ),
        ));
        
        
    }
}