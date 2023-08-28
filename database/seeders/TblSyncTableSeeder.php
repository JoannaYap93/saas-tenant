<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TblSyncTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_sync')->delete();
        
        \DB::table('tbl_sync')->insert(array (
            0 => 
            array (
                'sync_id' => 1,
                'sync_created' => '2022-07-20 02:14:36',
                'sync_updated' => '2022-07-20 02:14:36',
                'user_id' => 1,
                'company_id' => 1,
                'sync_file_identity' => '1l2tvieweyz1a3hckf3kgu43unostrx6',
                'is_reverted' => 0,
            ),
            1 => 
            array (
                'sync_id' => 596,
                'sync_created' => '2022-04-11 14:22:48',
                'sync_updated' => '2022-04-11 14:22:48',
                'user_id' => 2,
                'company_id' => 1,
                'sync_file_identity' => 'eei0uo3kf05q4w167mhtt6r0h8qakf1v',
                'is_reverted' => 0,
            ),
            2 => 
            array (
                'sync_id' => 6119,
                'sync_created' => '2023-01-10 08:50:32',
                'sync_updated' => '2023-01-10 08:50:32',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => 'rhaue1ojdyb3pftxo0125wg4zdrtibpo',
                'is_reverted' => 0,
            ),
            3 => 
            array (
                'sync_id' => 6122,
                'sync_created' => '2023-01-10 09:05:51',
                'sync_updated' => '2023-01-10 09:05:51',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => 'x6kbkxlfxb28msayp7npw6gq4iubwy9o',
                'is_reverted' => 0,
            ),
            4 => 
            array (
                'sync_id' => 6123,
                'sync_created' => '2023-01-10 09:08:38',
                'sync_updated' => '2023-01-10 09:08:38',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => 'dr9tm3z0ah07ju8vybrtli83divi6csy',
                'is_reverted' => 0,
            ),
            5 => 
            array (
                'sync_id' => 6463,
                'sync_created' => '2023-01-15 09:01:50',
                'sync_updated' => '2023-01-15 09:01:50',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => '3688a2yvqjtrmx9dhc6hkxjzz4axeojm',
                'is_reverted' => 0,
            ),
            6 => 
            array (
                'sync_id' => 6475,
                'sync_created' => '2023-01-15 10:49:11',
                'sync_updated' => '2023-01-15 10:49:11',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => 'tivt9bvg2fo9uiec4z6owpanh5i0jqo7',
                'is_reverted' => 0,
            ),
            7 => 
            array (
                'sync_id' => 6480,
                'sync_created' => '2023-01-15 11:00:36',
                'sync_updated' => '2023-01-15 11:00:36',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => '13y4k7mvlqrlgp1yspapcx4kv4ue2bdo',
                'is_reverted' => 0,
            ),
            8 => 
            array (
                'sync_id' => 6487,
                'sync_created' => '2023-01-15 11:32:17',
                'sync_updated' => '2023-01-15 11:32:17',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => 'vql01sumqswmxo04yve0cdwiyoe2umk5',
                'is_reverted' => 0,
            ),
            9 => 
            array (
                'sync_id' => 6488,
                'sync_created' => '2023-01-15 11:37:22',
                'sync_updated' => '2023-01-15 11:37:22',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => 'rio05ub4sf6fthyaxez6pvuyqde1tvnp',
                'is_reverted' => 0,
            ),
            10 => 
            array (
                'sync_id' => 6489,
                'sync_created' => '2023-01-15 11:38:49',
                'sync_updated' => '2023-01-15 11:38:49',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => 'i63r2xdsawlstnvijowyrreosdps7oc6',
                'is_reverted' => 0,
            ),
            11 => 
            array (
                'sync_id' => 6506,
                'sync_created' => '2023-01-15 13:58:19',
                'sync_updated' => '2023-01-15 13:58:19',
                'user_id' => 4,
                'company_id' => 2,
                'sync_file_identity' => '43mr7n21thc7h8zf420i40yijk8wy4j1',
                'is_reverted' => 0,
            ),
            12 => 
            array (
                'sync_id' => 6507,
                'sync_created' => '2023-01-15 14:02:56',
                'sync_updated' => '2023-01-15 14:02:56',
                'user_id' => 4,
                'company_id' => 2,
                'sync_file_identity' => 'dgxudlv1cls2cir7kxjjrqdjtccu9so8',
                'is_reverted' => 0,
            ),
            13 => 
            array (
                'sync_id' => 7054,
                'sync_created' => '2023-01-29 20:57:22',
                'sync_updated' => '2023-01-29 20:57:22',
                'user_id' => 4,
                'company_id' => 2,
                'sync_file_identity' => 'lbvur4q9zxa7joh8rzq423zs3ex6dbpx',
                'is_reverted' => 0,
            ),
            14 => 
            array (
                'sync_id' => 7057,
                'sync_created' => '2023-01-29 21:20:04',
                'sync_updated' => '2023-01-29 21:20:04',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => '6r6ypyt3jp216pwc5xv47bkqko7o96fo',
                'is_reverted' => 0,
            ),
            15 => 
            array (
                'sync_id' => 7058,
                'sync_created' => '2023-01-29 21:22:34',
                'sync_updated' => '2023-01-29 21:22:34',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => 'mcut9fpgnn4le9qmikwbljoqzhpfu8xq',
                'is_reverted' => 0,
            ),
            16 => 
            array (
                'sync_id' => 7059,
                'sync_created' => '2023-01-29 21:37:46',
                'sync_updated' => '2023-01-29 21:37:46',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => 'bp8r349y0uflzxoiu356cbu6mjzhmlbu',
                'is_reverted' => 0,
            ),
            17 => 
            array (
                'sync_id' => 7061,
                'sync_created' => '2023-01-29 21:46:59',
                'sync_updated' => '2023-01-29 21:46:59',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => '48oqlq3ej1biwy4j5mxsr6oyos8xwvfb',
                'is_reverted' => 0,
            ),
            18 => 
            array (
                'sync_id' => 7108,
                'sync_created' => '2023-01-31 21:52:58',
                'sync_updated' => '2023-01-31 21:52:58',
                'user_id' => 4,
                'company_id' => 2,
                'sync_file_identity' => 'cpawiie05r6adlpmdojca5csz6wukv3i',
                'is_reverted' => 0,
            ),
            19 => 
            array (
                'sync_id' => 7109,
                'sync_created' => '2023-01-31 21:58:26',
                'sync_updated' => '2023-01-31 21:58:26',
                'user_id' => 4,
                'company_id' => 2,
                'sync_file_identity' => 'xyp80n54fo2jmwqwejtpybz1alml7y53',
                'is_reverted' => 0,
            ),
            20 => 
            array (
                'sync_id' => 7110,
                'sync_created' => '2023-01-31 22:01:19',
                'sync_updated' => '2023-01-31 22:01:19',
                'user_id' => 4,
                'company_id' => 2,
                'sync_file_identity' => 'enkrixw5b1suix5f3az9b8u89go9xbot',
                'is_reverted' => 0,
            ),
            21 => 
            array (
                'sync_id' => 7112,
                'sync_created' => '2023-01-31 22:04:22',
                'sync_updated' => '2023-01-31 22:04:22',
                'user_id' => 4,
                'company_id' => 2,
                'sync_file_identity' => 'f53f5stumga8p3hrnw4lnchdu9wvxjzq',
                'is_reverted' => 0,
            ),
            22 => 
            array (
                'sync_id' => 7114,
                'sync_created' => '2023-01-31 22:13:31',
                'sync_updated' => '2023-01-31 22:13:31',
                'user_id' => 4,
                'company_id' => 2,
                'sync_file_identity' => '91l9doesv3emjeuqb2qiqli0q02lxd5l',
                'is_reverted' => 0,
            ),
            23 => 
            array (
                'sync_id' => 8031,
                'sync_created' => '2023-05-08 09:57:24',
                'sync_updated' => '2023-05-08 09:57:24',
                'user_id' => 3,
                'company_id' => 2,
                'sync_file_identity' => 'pm9apjtpnublqdaasgeeq6av4cqb1hp6',
                'is_reverted' => 0,
            ),
        ));
        
        
    }
}