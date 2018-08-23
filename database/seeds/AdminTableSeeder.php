<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::drop('admin');
        DB::collection('admin')->insert([
            'hoten' => 'Bùi Văn Lực',
            'taikhoan' => 'boynb98',
            'email' => 'builuc1998@gmail.com',
            'matkhau' => md5('ailatoi1'),
            'dienthoai' => '01638868302',
            'fbid' => '100004520190007',
            'level' => '0',
            'money'=>'99999999',
            'created_at'=> time(),
            'updated_at'=> time()
        ]);
        DB::collection('admin')->insert([
            'hoten' => 'Bùi Văn Lực',
            'taikhoan' => 'admin',
            'email' => 'admin@gmail.com',
            'matkhau' => md5('ailatoi1'),
            'dienthoai' => '01638868302',
            'fbid' => '100004520190007',
            'level' => '0',
            'money'=>'99999999',
            'created_at'=> time(),
            'updated_at'=> time()
        ]);
    }
}
